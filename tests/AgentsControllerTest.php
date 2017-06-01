<?php

class AgentsControllerTest extends TestCase
{
	// Agents controller test. used by global-admin

	/**
	 * Test get routes.
	 */
	public function testGetRoutes()
	{
		// Create test user.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent = $users['agent'];

		// Get routes.
		$this->actingAs($global_admin)
			->visit('/agents')
			->assertResponseStatus(200)
			->visit('/agents/create')
			->assertResponseStatus(200)
			->visit('/agents/edit/' . $agent->id)
			->assertResponseStatus(200)
			->visit('/agents/view/' . $agent->id)
			->assertResponseStatus(200)
			->visit('/agents/create-user/' . $agent->id)
			->assertResponseStatus(200);

		// Get /agents with phrase
		$this->actingAs($global_admin)
			->get('/agents?phrase=testphrase')
			->assertResponseStatus(200);
	}

	/**
	 * Test post agent creation
	 * POST: /agents/create
	 */
	public function testPostCreate()
	{
		// Create test user.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];

		// Get Create
		$data_new_agent = [
			'name'              => 'TestName',
			'email'             => 'testEmail1@com.com',
			'billing_email'     => 'testEmail2@com.com',
			'abn'               => '123456789',
			'shipping_address'  => '2345',
			'billing_address'   => '3456',
			'shipping_suburb'   => '4567',
			'billing_suburb'    => '5678',
			'shipping_state'    => 'ACT',
			'billing_state'     => 'NSW',
			'shipping_postcode' => '6789',
			'billing_postcode'  => '7890',
			'phone'             => '89012345',
			'mobile'            => '90123456',
			'fax'               => '12341234',
			'bank_acc_name'     => '22342345',
			'bank_acc_no'       => '32341234',
			'bank_acc_bsb'      => '8901',
		];

		$this->actingAs($global_admin)
			->post('/agents/create', $data_new_agent);

		// Check db for creation.
		$this->seeInDatabase('agents', $data_new_agent);
	}

	/**
	 * Test post edit
	 * POST: /agents/edit/{agent_id}
	 */
	public function testPostEdit()
	{
		// Create test user.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent = $users['agent'];

		// Post edit
		$data_new_agent = [
			'name'              => 'TestName',
			'email'             => 'testEmail1@com.com',
			'billing_email'     => 'testEmail2@com.com',
			'abn'               => '123456789',
			'shipping_address'  => '2345',
			'billing_address'   => '3456',
			'shipping_suburb'   => '4567',
			'billing_suburb'    => '5678',
			'shipping_state'    => 'ACT',
			'billing_state'     => 'NSW',
			'shipping_postcode' => '6789',
			'billing_postcode'  => '7890',
			'phone'             => '89012345',
			'mobile'            => '90123456',
			'fax'               => '12341234',
			'bank_acc_name'     => '22342345',
			'bank_acc_no'       => '32341234',
			'bank_acc_bsb'      => '8901',
		];

		$this->actingAs($global_admin)
			->post('/agents/edit/' . $agent->id, $data_new_agent);

		// Check db for edition.
		$this->seeInDatabase('agents', $data_new_agent);
	}

	/**
	 * Test post delete
	 * POST: /agents/delete/{agent_id}
	 */
	public function testPostDelete()
	{
		// Create test user.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];

		$agent_new = factory(App\Agent::class)->create();

		// Test post delete.
		$this->actingAs($global_admin)
			->post('/agents/delete', [
				'agent_id' => $agent_new->id,
			]);

		// Check DB for deletion.
		$this->dontSeeInDatabase('agents', $agent_new->toArray());
	}

	/**
	 * Test post create-user (modal)
	 * POST: /agents/create-user/{agent_id}
	 */
	public function testPostCreateUser()
	{
		// Create test user.
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent = $users['agent'];

		// Test post create-user.
		$this->actingAs($global_admin)
			->post('/agents/create-user', [
				'first_name'            => 'TestFirstName',
				'last_name'             => 'TestLastName',
				'email'                 => 'TestEmail@com.com',
				'password'              => 'password',
				'password_confirmation' => 'password',
				'type'                  => 'agent-admin',
				'agent_id'              => $agent->id,
			]);

		// Check DB for creation.
		$this->seeInDatabase('users', [
			'first_name' => 'TestFirstName',
			'last_name'  => 'TestLastName',
			'email'      => 'TestEmail@com.com',
			'type'       => 'agent-admin',
			'agent_id'   => $agent->id,
		]);
	}

	// Helper functions for tests

	/**
	 * Creates a testing agent, global-admin
	 */
	public function createTestUser()
	{
		$global_admin = factory(App\User::class, 'global-admin')->create();
		$agent = factory(App\Agent::class)->create();

		return [
			'global-admin' => $global_admin,
			'agent'        => $agent,
		];
	}

}
