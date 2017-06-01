<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APIUsersControllerTest extends TestCase
{
	// Api/UsersController test

	/**
	 * Test get profile function.
	 *
	 * @return void
	 */
	public function testGetProfile()
	{
		// Create a test User.
		$agent = factory(App\Agent::class)->create();

		$user = factory(App\User::class, 'agent-admin')->create([
			'agent_id'  => $agent->id,
			'api_token' => 'testToken123',
		]);

		$this->actingAs($user)
			->json('GET', '/api/users/profile/' . $user->id, [
				'api_token' => $user->api_token,
			])
			->assertResponseStatus(200)
			->seeJsonStructure([
				'id',
				'agent_id',
				'first_name',
				'last_name',
				'type',
				'email',
				'timezone',
			])
			->seeJsonSubset([
				'id'         => $user->id,
				'agent_id'   => $user->agent_id,
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'type'       => $user->type,
				'email'      => $user->email,
				'timezone'   => $user->timezone,
			]);
	}

	/**
	 * Test get invite
	 * GET: /api/users/invite
	 */
	public function testGetInvite()
	{
		// Create a test User.
		$agent = factory(App\Agent::class)->create();

		$user = factory(App\User::class, 'agent-admin')->create([
			'agent_id'  => $agent->id,
			'api_token' => 'testToken123',
		]);

		// Test without invitation token
		$this->actingAs($user)
			->json('GET', '/api/users/invite/', [
				'api_token' => $user->api_token,
			])
			->seeJson([
				'Error' => 'No token provided',
			]);

		// Test with invitation token
		$invitation = factory(App\Invitation::class)->create([
			'user_id' => $user->id,
		]);

		$this->actingAs($user)
			->json('GET', '/api/users/invite/' . $invitation->token, [
				'api_token' => $user->api_token,
			])
			->seeJsonSubset([
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
			]);

		// Test with wrong token.
		$this->actingAs($user)
			->json('GET', '/api/users/invite/' . str_random(5), [
				'api_token' => $user->api_token,
			])
			->seeJson(['Error' => 'No invitation found']);
	}

	/**
	 * Test edit profile function.
	 *
	 * @return void
	 */
	public function testEditProfile()
	{
		// Create a test User.
		$agent = factory(App\Agent::class)->create();

		$user = factory(App\User::class, 'agent-admin')->create([
			'agent_id'  => $agent->id,
			'api_token' => 'testToken123',
			'type'      => 'installer',
		]);

		$this->actingAs($user)
			->json('POST', '/api/users/edit/' . $user->id, [
				'api_token' => $user->api_token,
				'password'  => 'NewPassword',
			])
			->seeJsonEquals([
				'email'      => ['The email field is required.'],
				'first_name' => ['The first name field is required.'],
				'last_name'  => ['The last name field is required.'],
				'password'   => ['The password confirmation does not match.'],
			])
			->assertResponseStatus(422);

		$this->actingAs($user)
			->json('POST', '/api/users/edit/' . $user->id, [
				'api_token'  => $user->api_token,
				'first_name' => 'valid',
				'last_name'  => 'valid',
				'email'      => 'invalid',
			])
			->seeJsonEquals([
				'email' => ['The email must be a valid email address.'],
			])
			->assertResponseStatus(422);

		$this->actingAs($user)
			->json('POST', '/api/users/edit/' . $user->id, [
				'api_token'             => $user->api_token,
				'first_name'            => 'John',
				'last_name'             => 'Doe',
				'email'                 => $user->email,
				'password'              => 'NewPassword',
				'password_confirmation' => 'NewPassword',
			])
			->assertResponseStatus(200);

		$this->seeInDatabase('users', [
			'id'         => $user->id,
			'email'      => $user->email,
			'first_name' => 'John',
			'last_name'  => 'Doe',
		]);

		$this->dontSeeInDatabase('users', [
			'password' => $user->password,
		]);
	}

}
