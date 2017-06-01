<?php

class InstallersControllerTest extends TestCase
{
	// Clients Controller test

	/**
	 * Test get routes
	 * GET: /installers
	 * GET: /installers/view/{installer_id}
	 * GET: /installers/invite
	 */
	public function testGetRoutes()
	{
		// Create test users.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Create a installer to be seen.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Visit index.
		$this->actingAs($agent_admin)
			->visit('/installers')
			->assertResponseStatus(200)
			->visit('/installers/view/' . $installer->id)
			->assertResponseStatus(200)
			->see($installer->first_name)
			->see($installer->last_name)
			->visit('/installers/invite')
			->assertResponseStatus(200);
	}

	/**
	 * test post delete, this only detaches installer from agent.
	 * POST: /installers/delete/{installer_id}
	 */
	public function testPostDelete()
	{
		// Create test users.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Create a installer to be seen.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Do detach by post
		$this->actingAs($agent_admin)
			->post('/installers/delete/' . $installer->id, []);

		// Do detach by Ajax with XHR header
		$this->actingAs($agent_admin)
			->post('/installers/delete/' . $installer->id, [], ['X-Requested-With' => 'XMLHttpRequest']);

		// Check db for detach.
		$this->dontSeeInDatabase('agent_installers', [
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);
	}

	/**
	 * test post invite from front-end
	 * POST: /installers/invite
	 */
	public function testPostInvite()
	{
		// Create test users.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Create a installer to attach.
		$installer = factory(App\User::class, 'installer')->create();

		// Test validation.
		$this->actingAs($agent_admin)
			->visit('/installers/invite')
			->press('Create');

		$this->see('The first name field is required.')
			->see('The last name field is required.')
			->see('The email field is required.');

		// Test attach installer.
		$this->actingAs($agent_admin)
			->visit('/installers/invite')
			->type($installer->first_name, 'first_name')
			->type($installer->last_name, 'last_name')
			->type($installer->email, 'email')
			->press('Create');

		// Check db for pivot table.
		$this->seeInDatabase('agent_installers', [
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Try creating an installer using an admin email address
		$this->actingAs($agent_admin)
			->visit('/installers/invite')
			->type('John', 'first_name')
			->type('Smith', 'last_name')
			->type($agent_admin->email, 'email')
			->press('Create');

		$this->seePageIs('/installers/invite')
			->see('This email address is already in use by an agent or administrator user.');

		// Invite brand-new installer.
		$new_installer_data = [
			'first_name' => 'NewInstallerFirstName',
			'last_name'  => 'NewInstallerLastName',
			'email'      => 'NewInstallerEmail@com.com',
			'type'       => 'installer',
		];
		$this->actingAs($agent_admin)
			->visit('/installers/invite')
			->type($new_installer_data['first_name'], 'first_name')
			->type($new_installer_data['last_name'], 'last_name')
			->type($new_installer_data['email'], 'email')
			->press('Create');

		// Check db for user creation.
		$this->seeInDatabase('users', $new_installer_data);

		// Check db for pivot table.
		$new_installer = App\User::where('email', $new_installer_data['email'])->first();
		$this->seeInDatabase('agent_installers', [
			'agent_id'     => $agent->id,
			'installer_id' => $new_installer->id,
		]);

		// Check db for invitation creation.
		$this->seeInDatabase('invitations', [
			'user_id' => $new_installer->id,
		]);
	}

	/**
	 * test get resend
	 * GET: /installers/resend/{installer_id}
	 */
	public function testGetResend()
	{
		// Create test users.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Create a installer to be seen.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Test get.
		$this->actingAs($agent_admin)
			->get('/installers/resend/' . $installer->id)
			->assertResponseStatus(200);
	}

	/**
	 * test post resend
	 * POST: /installers/resend/{installer_id}
	 */
	public function testPostResend()
	{
		// Create test users.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Create a installer to be seen.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		$invitation = factory(App\Invitation::class)->create([
			'user_id' => $installer->id,
		]);

		// Test post.
		$this->actingAs($agent_admin)
			->post('/installers/resend/' . $installer->id)
			->assertResponseStatus(200);
	}

	// Helper functions for tests

	/**
	 * Creates a testing agent, agent-admin
	 */
	public function createTestUser()
	{
		$agent = factory(App\Agent::class)->create();
		$agent_admin = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);

		return [
			'agent'       => $agent,
			'agent-admin' => $agent_admin,
		];
	}

}
