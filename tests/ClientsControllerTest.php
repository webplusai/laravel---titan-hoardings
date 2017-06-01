<?php

class ClientsControllerTest extends TestCase
{
	// Clients Controller test

	/**
	 * Test get index
	 * GET: /clients
	 */
	public function testGetIndex()
	{
		// Create users for test.
		$users = $this->createTestUser();
		$user = $users['agent-admin'];

		$this->actingAs($user)
			->visit('/clients')
			->assertResponseStatus(200);
	}

	/**
	 * Test get view
	 * GET: /clients/view/{client_id}
	 */
	public function testGetView()
	{
		// Create users for test.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];

		// Create a test client
		$client = $this->createTestClient($agent->id);

		// Test successful get.
		$this->actingAs($user)
			->visit('/clients/view/' . $client->id)
			->assertResponseStatus(200)
			->see($client->name);

		$this->actingAs($user)
			->get('/clients/view/999')
			->assertResponseStatus(404);
	}

	/**
	 * Test client creation, Get view => Post values from front-end
	 * Front-end create client
	 * GET: /clients/create
	 */
	public function testGetCreate()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];

		// Test Create client.
		$this->actingAs($user)
			->visit('/clients/create')
			->assertResponseStatus(200)
			->type('NewClient', 'name')
			->type('testclient@com.com', 'email')
			->type('testclient@com.com', 'billing_email')
			->type('123456789', 'abn')
			->type('1234', 'billing_address')
			->type('1234', 'shipping_address')
			->type('1234', 'billing_suburb')
			->type('1234', 'shipping_suburb')
			->select('ACT', 'billing_state')
			->select('ACT', 'shipping_state')
			->type('1234', 'billing_postcode')
			->type('1234', 'shipping_postcode')
			->type('12345678', 'phone')
			->type('12345678', 'mobile')
			->type('12345678', 'fax')
			->press('Create');

		// Check DB.
		$this->seeInDatabase('clients', [
			'agent_id'          => $agent->id,
			'name'              => 'NewClient',
			'email'             => 'testclient@com.com',
			'billing_email'     => 'testclient@com.com',
			'mobile'            => '12345678',
			'fax'               => '12345678',
			'abn'               => '123456789',
			'billing_address'   => '1234',
			'billing_suburb'    => '1234',
			'billing_state'     => 'ACT',
			'billing_postcode'  => '1234',
			'shipping_address'  => '1234',
			'shipping_suburb'   => '1234',
			'shipping_state'    => 'ACT',
			'shipping_postcode' => '1234',
			'phone'             => '12345678',
		]);
	}

	/**
	 * Test post create with XHR header
	 * POST: /clients/create
	 */
	public function testPostCreateJSON()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];

		// Test Create client.
		$this->actingAs($user)
			->post('/clients/create', [
				'name'              => 'NewClient',
				'email'             => 'testclient@com.com',
				'billing_email'     => 'testclient@com.com',
				'abn'               => '123456789',
				'billing_address'   => '1234',
				'shipping_address'  => '1234',
				'billing_suburb'    => '1234',
				'shipping_suburb'   => '1234',
				'billing_state'     => 'ACT',
				'shipping_state'    => 'ACT',
				'billing_postcode'  => '1234',
				'shipping_postcode' => '1234',
				'phone'             => '12345678',
				'mobile'            => '12345678',
				'fax'               => '12345678',
			], ['X-Requested-With' => 'XMLHttpRequest']);

		// Check DB.
		$this->seeInDatabase('clients', [
			'agent_id'          => $agent->id,
			'name'              => 'NewClient',
			'email'             => 'testclient@com.com',
			'billing_email'     => 'testclient@com.com',
			'mobile'            => '12345678',
			'fax'               => '12345678',
			'abn'               => '123456789',
			'billing_address'   => '1234',
			'billing_suburb'    => '1234',
			'billing_state'     => 'ACT',
			'billing_postcode'  => '1234',
			'shipping_address'  => '1234',
			'shipping_suburb'   => '1234',
			'shipping_state'    => 'ACT',
			'shipping_postcode' => '1234',
			'phone'             => '12345678',
		]);
	}

	/**
	 * Test client edit, Get view => Post values from front-end
	 * Front-end edit client
	 * GET: /clients/edit/{client_id}
	 */
	public function testGetEdit()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];

		// Create test client.
		$client = $this->createTestClient($agent->id);

		// Test edit client.
		$this->actingAs($user)
			->visit('/clients/edit/' . $client->id)
			->assertResponseStatus(200)
			->type('NewClient', 'name')
			->type('testClient@com.com', 'email')
			->type('testClient@com.com', 'billing_email')
			->type('123456789', 'abn')
			->type('1234', 'billing_address')
			->type('1234', 'shipping_address')
			->type('1234', 'billing_suburb')
			->type('1234', 'shipping_suburb')
			->select('ACT', 'billing_state')
			->select('ACT', 'shipping_state')
			->type('1234', 'billing_postcode')
			->type('1234', 'shipping_postcode')
			->type('12345678', 'phone')
			->type('12345678', 'mobile')
			->type('12345678', 'fax')
			->press('Update');

		// Check DB.
		$this->seeInDatabase('clients', [
			'agent_id'          => $agent->id,
			'name'              => 'NewClient',
			'email'             => 'testClient@com.com',
			'billing_email'     => 'testClient@com.com',
			'mobile'            => '12345678',
			'fax'               => '12345678',
			'abn'               => '123456789',
			'billing_address'   => '1234',
			'billing_suburb'    => '1234',
			'billing_state'     => 'ACT',
			'billing_postcode'  => '1234',
			'shipping_address'  => '1234',
			'shipping_suburb'   => '1234',
			'shipping_state'    => 'ACT',
			'shipping_postcode' => '1234',
			'phone'             => '12345678',
		]);
	}

	/**
	 * Test post delete
	 * POST: /clients/delete
	 */
	public function testPostDelete()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];

		// Create test client.
		$client = $this->createTestClient($agent->id);

		// Add contact to client.
		factory(App\Contact::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
		]);

		// Do delete.
		$this->actingAs($user)
			->post('/clients/delete', [
				'client_id' => $client->id,
			]);

		// Check DB, client has deleted.
		$this->dontSeeInDatabase('clients', [
			'id'       => $client->id,
			'agent_id' => $agent->id,
			'name'     => $client->name,
		]);

		// Check DB, contact has deleted.
		$this->dontSeeInDatabase('contacts', [
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
		]);
	}

	/**
	 * Test get typehead
	 * GET: /clients/typeahead/{query}
	 */
	public function testGetTypeahead()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];

		// Create test client.
		$client = $this->createTestClient($agent->id);

		// Do get
		$this->actingAs($user)
			->get('/clients/typeahead/' . substr($client->name, 0, 3))
			->assertResponseStatus(200)
			->see($client->name);
	}

	// Helper functions for tests

	/**
	 * Creates a testing agent, agent-admin, client
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

	/**
	 * Creates test clients.
	 * @param $agent_id int agent id.
	 * @return App\Client created.
	 */
	public function createTestClient($agent_id)
	{
		return factory(App\Client::class)->create([
			'agent_id' => $agent_id,
			'name'     => 'TestClient',
		]);
	}

}
