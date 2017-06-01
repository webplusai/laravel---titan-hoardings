<?php

class ContactsControllerTest extends TestCase
{
	// Contacts controller test

	/**
	 * Test post create
	 * POST: /contacts/create
	 */
	public function testPostCreate()
	{
		// Create test users
		$users = $this->createTestUser();
		$user = $users['agent-admin'];
		$client = $users['client'];
		$agent = $users['agent'];

		// Test create, add to client
		$this->actingAs($user)
			->post('/contacts/create', [
				'client_id' => $client->id,
				'name'      => 'Test Contact',
				'position'  => 'TestPosition',
				'type'      => 'Client',
				'email'     => 'TestContact@com.com',
				'phone'     => '12345678',
			])
			->assertResponseStatus(200);

		// Check DB.
		$this->seeInDatabase('contacts', [
			'client_id'  => $client->id,
			'agent_id'   => $agent->id,
			'first_name' => 'Test',
			'last_name'  => 'Contact',
			'position'   => 'TestPosition',
			'type'       => 'Client',
			'email'      => 'TestContact@com.com',
		]);

		// Test create, add to job.
		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		$this->actingAs($user)
			->post('/contacts/create', [
				'client_id'         => $client->id,
				'name'              => 'Test2 Contact2',
				'position'          => 'TestPosition2',
				'type'              => 'Other',
				'email'             => 'TestContact2@com.com',
				'phone'             => '22345678',
				'job_id'            => $job->id,
				'client_associated' => false,
			])
			->assertResponseStatus(200);

		// Check DB.
		$this->seeInDatabase('contacts', [
			'agent_id'   => $agent->id,
			'first_name' => 'Test2',
			'last_name'  => 'Contact2',
			'position'   => 'TestPosition2',
			'type'       => 'Other',
			'email'      => 'TestContact2@com.com',
		]);

		$contact_job = App\Contact::where('email', 'TestContact2@com.com')->first();
		$this->seeInDatabase('job_contacts', [
			'job_id'     => $job->id,
			'contact_id' => $contact_job->id,
		]);
	}

	/**
	 * Test post edit
	 * POST: /contacts/edit/{contact_id}
	 */
	public function testPostEdit()
	{
		// Create test users
		$users = $this->createTestUser();
		$user = $users['agent-admin'];
		$client = $users['client'];
		$agent = $users['agent'];

		// Create test contact.
		$contact = factory(App\Contact::class)->create([
			'agent_id'   => $agent->id,
			'client_id'  => $client->id,
			'first_name' => 'Test',
			'last_name'  => 'Contact',
		]);

		// Do edit.
		$this->actingAs($user)
			->post('/contacts/edit/' . $contact->id, [
				'client_id' => $client->id,
				'name'      => 'Test Contact',
				'position'  => 'TestPosition',
				'type'      => 'Client',
				'email'     => 'TestContact@com.com',
				'phone'     => '12345678',
			])
			->assertResponseStatus(200);

		// Check DB.
		$this->seeInDatabase('contacts', [
			'client_id'  => $client->id,
			'agent_id'   => $agent->id,
			'first_name' => 'Test',
			'last_name'  => 'Contact',
			'position'   => 'TestPosition',
			'type'       => 'Client',
			'email'      => 'TestContact@com.com',
		]);
	}

	/**
	 * Test post delete
	 * POST: /contacts/delete/{contact_id}
	 */
	public function testPostDelete()
	{
		// Create test users
		$users = $this->createTestUser();
		$user = $users['agent-admin'];
		$client = $users['client'];
		$agent = $users['agent'];

		// Create test contact.
		$contact = factory(App\Contact::class)->create([
			'agent_id'   => $agent->id,
			'client_id'  => $client->id,
			'first_name' => 'Test',
			'last_name'  => 'Contact',
		]);

		// Do delete.
		$this->actingAs($user)
			->post('/contacts/delete/' . $contact->id, [])
			->assertResponseStatus(200);

		// Check DB.
		$this->dontSeeInDatabase('contacts', [
			'id'        => $contact->id,
			'client_id' => $client->id,
			'agent_id'  => $agent->id,
		]);
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
		$client = factory(App\Client::class)->create([
			'agent_id' => $agent->id,
		]);

		return [
			'agent'       => $agent,
			'agent-admin' => $agent_admin,
			'client'      => $client,
		];
	}

	/**
	 * creates a testing job
	 * @param $agent  App\Agent agent from createTestUsers
	 * @param $user   App\User agent-admin from createTestUsers
	 * @param $client App\Client client from createTestUsers
	 * @return mixed job created.
	 */
	public function createTestJob($agent, $user, $client)
	{
		$job = factory(App\Job::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
			'type'      => 'installation',
		]);

		$job->jsra()->create([]);
		$job->qc()->create([]);

		return $job;
	}

}
