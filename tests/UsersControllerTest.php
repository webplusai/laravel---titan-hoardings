<?php

class UsersControllerTest extends TestCase
{
	// Users controller test

	/**
	 * Test get routes
	 */
	public function testGetRoutes()
	{
		// Create test users.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$global_admin = $users['global-admin'];
		$agent_admin = $users['agent-admin'];

		// Create new users to be seen.
		$new_global_admin = factory(App\User::class, 'global-admin')->create();
		$new_agent_admin = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);

		// Test as global-admin
		$this->actingAs($global_admin)
			->visit('/users')
			->assertResponseStatus(200)
			->see($global_admin->first_name)
			->dontSee($agent_admin->first_name)
			->visit('/users/create')
			->assertResponseStatus(200)
			->visit('/users/view/' . $new_global_admin->id)
			->assertResponseStatus(200)
			->see($new_global_admin->first_name)
			->visit('/users/edit/' . $new_global_admin->id)
			->assertResponseStatus(200)
			->visit('/users/invite')
			->assertResponseStatus(200);

		// Test as agent-admin
		$this->actingAs($agent_admin)
			->visit('/users')
			->assertResponseStatus(200)
			->see($agent_admin->first_name)
			->dontSee($global_admin->first_name)
			->visit('/users/create')
			->assertResponseStatus(200)
			->visit('/users/view/' . $new_agent_admin->id)
			->assertResponseStatus(200)
			->see($new_agent_admin->first_name)
			->visit('/users/edit/' . $new_agent_admin->id)
			->assertResponseStatus(200);
	}

	/**
	 * Test Post create from front-end
	 * GET: /users/create
	 * POST: /users/create
	 */
	public function testPostCreate()
	{
		// Create test users.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Test as global-admin
		$data_global_admin = [
			'first_name' => 'TestFirstName',
			'last_name'  => 'TestLastName',
			'email'      => 'testGlobalAdmin2@com.com',
			'type'       => 'global-admin',
		];
		$this->actingAs($global_admin)
			->visit('/users/create')
			->assertResponseStatus(200)
			->type($data_global_admin['first_name'], 'first_name')
			->type($data_global_admin['last_name'], 'last_name')
			->type($data_global_admin['email'], 'email')
			->select($data_global_admin['type'], 'type')
			->type('password', 'password')
			->type('password', 'password_confirmation')
			->press('Create');

		// Check DB.
		$this->seeInDatabase('users', $data_global_admin);

		// Test as agent-admin
		$data_agent_admin = [
			'first_name' => 'TestFirstName2',
			'last_name'  => 'TestLastName2',
			'email'      => 'testAgentAdmin2@com.com',
			'type'       => 'agent-admin',
			'agent_id'   => $agent->id,
		];
		$this->actingAs($agent_admin)
			->visit('/users/create')
			->assertResponseStatus(200)
			->type($data_agent_admin['first_name'], 'first_name')
			->type($data_agent_admin['last_name'], 'last_name')
			->type($data_agent_admin['email'], 'email')
			->select($data_agent_admin['type'], 'type')
			->select($data_agent_admin['agent_id'], 'agent_id')
			->type('password', 'password')
			->type('password', 'password_confirmation')
			->press('Create');

		// Check DB.
		$this->seeInDatabase('users', $data_agent_admin);
	}

	/**
	 * Test Post edit from front-end
	 * GET: /users/edit/{user_id}
	 * POST: /users/edit/{user_id}
	 */
	public function testPostEdit()
	{
		// Create test users.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Create users to be edited.
		$new_global_admin = factory(App\User::class, 'global-admin')->create();
		$new_agent_admin = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);

		// Test as global-admin
		$data_global_admin = [
			'first_name' => 'NewFirstName',
			'last_name'  => 'NewLastName',
			'email'      => 'newGlobalAdmin2@com.com',
			'type'       => 'global-admin',
		];
		$this->actingAs($global_admin)
			->visit('/users/edit/' . $new_global_admin->id)
			->assertResponseStatus(200)
			->type($data_global_admin['first_name'], 'first_name')
			->type($data_global_admin['last_name'], 'last_name')
			->type($data_global_admin['email'], 'email')
			->select($data_global_admin['type'], 'type')
			->type('password', 'password')
			->type('password', 'password_confirmation')
			->press('Update');

		// Check DB.
		$this->seeInDatabase('users', $data_global_admin);

		// Test as agent-admin
		$data_agent_admin = [
			'first_name' => 'NewFirstName2',
			'last_name'  => 'NewLastName2',
			'email'      => 'newAgentAdmin2@com.com',
			'type'       => 'agent-admin',
			'agent_id'   => $agent->id,
		];
		$this->actingAs($agent_admin)
			->visit('/users/edit/' . $new_agent_admin->id)
			->assertResponseStatus(200)
			->type($data_agent_admin['first_name'], 'first_name')
			->type($data_agent_admin['last_name'], 'last_name')
			->type($data_agent_admin['email'], 'email')
			->select($data_agent_admin['type'], 'type')
			->select($data_agent_admin['agent_id'], 'agent_id')
			->type('password', 'password')
			->type('password', 'password_confirmation')
			->press('Update');

		// Check DB.
		$this->seeInDatabase('users', $data_agent_admin);
	}

	/**
	 * Test post delete
	 * POST: /users/delete/{user_id}
	 */
	public function testPostDelete()
	{
		// Create test users.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];

		// Create new users to be seen.
		$new_agent_admin = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);
		$new_global_admin = factory(App\User::class, 'global-admin')->create();

		// Do post delete as Agent admin
		$this->actingAs($agent_admin)
			->post('/users/delete/' . $new_agent_admin->id, []);

		// Check db for deletion
		$this->dontSeeInDatabase('users', [
			'first_name' => $new_agent_admin->first_name,
			'last_name'  => $new_agent_admin->last_name,
			'email'      => $new_agent_admin->email,
			'type'       => $new_agent_admin->type,
		]);

		// Do post delete as global admin
		$this->actingAs($global_admin)
			->post('/users/delete/' . $new_global_admin->id, []);

		// Check db for deletion
		$this->dontSeeInDatabase('users', [
			'first_name' => $new_global_admin->first_name,
			'last_name'  => $new_global_admin->last_name,
			'email'      => $new_global_admin->email,
			'type'       => $new_global_admin->type,
		]);
	}

	/**
	 * Test Post Invite
	 * GET: /users/invite
	 */
	public function testPostInvite()
	{
		// Create test users.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];

		// Post invite
		$data_invite = [
			'first_name' => 'TestInvite1',
			'last_name'  => 'TestInvite2',
			'email'      => 'testInvite@com.com',
			'type'       => 'Installer',
		];
		$this->actingAs($global_admin)
			->post('/users/invite', $data_invite);

		// Check db for invite creation
		$this->seeInDatabase('users', $data_invite);
		$invited_user = App\User::where('email', $data_invite['email'])->first();
		$this->seeInDatabase('invitations', [
			'user_id' => $invited_user->id,
		]);
	}

	// Helper functions for tests

	/**
	 * Creates a testing agent, agent-admin, global-admin
	 */
	public function createTestUser()
	{
		$global_admin = factory(App\User::class, 'global-admin')->create();

		$agent = factory(App\Agent::class)->create();
		$agent_admin = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);

		return [
			'global-admin' => $global_admin,
			'agent'        => $agent,
			'agent-admin'  => $agent_admin,
		];
	}

}
