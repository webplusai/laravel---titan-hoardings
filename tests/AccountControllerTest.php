<?php

class AccountControllerTest extends TestCase
{
	// Account controller test.

	/**
	 * Test get route post account information from front-end
	 * GET: /account
	 * POST: /account
	 */
	public function testAccountEdit()
	{
		// Create test purpose users
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent_admin = $users['agent-admin'];

		// Test as global-admin
		$this->actingAs($global_admin)
			->visit('/account')
			->assertResponseStatus(200)
			->type('NewGlobalFirstName', 'first_name')
			->type('NewGlobalLastName', 'last_name')
			->type('NewPassword', 'password')
			->type('NewPassword', 'password_confirmation')
			->press('Update');

		$this->seeInDatabase('users', [
			'first_name' => 'NewGlobalFirstName',
			'last_name'  => 'NewGlobalLastName',
		]);

		// Test as agent-admin
		$this->actingAs($agent_admin)
			->visit('/account')
			->assertResponseStatus(200)
			->type('NewAgentFirstName', 'first_name')
			->type('NewAgentLastName', 'last_name')
			->type('NewPassword2', 'password')
			->type('NewPassword2', 'password_confirmation')
			->press('Update');

		$this->seeInDatabase('users', [
			'first_name' => 'NewAgentFirstName',
			'last_name'  => 'NewAgentLastName',
		]);
	}

	/**
	 * Test post impersonate
	 * POST: /account/impersonate
	 */
	public function testPostImpersonate()
	{
		// Create test purpose users
		$users = $this->createTestUser();
		$global_admin = $users['global-admin'];
		$agent_admin = $users['agent-admin'];
		$agent = $users['agent'];

		// Test as global-admin
		$this->actingAs($global_admin)
			->post('/account/impersonate', [
				'agent_id' => $agent->id,
			]);

		$this->seeInDatabase('users', [
			'first_name' => $global_admin->first_name,
			'last_name'  => $global_admin->last_name,
			'agent_id'   => $agent->id,
		]);

		// Test as global-admin
		$this->actingAs($agent_admin)
			->post('/account/impersonate', [
				'agent_id' => $agent->id,
			])
			->assertResponseStatus(403);
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
