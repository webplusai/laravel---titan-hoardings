<?php

use Illuminate\Http\UploadedFile;

class APIAccountControllerTest extends TestCase
{
	// API/AccountController test

	/**
	 * Tests Get Account Profile API end point.
	 * EndPoint: /api/account/profile
	 */
	public function testGetProfile()
	{
		// Create a user for testing.
		$api_token = 'testToken123';
		$user = factory(App\User::class)->create([
			'api_token' => $api_token,
		]);

		// Test authorized access now.
		$this->actingAs($user)
			->json('GET', '/api/account/profile', [
				'api_token' => $api_token,
			])
			->assertResponseStatus(200)
			->seeJsonStructure([
				'id',
				'agent_id',
				'first_name',
				'last_name',
				'type',
				'email',
				'phone_main',
				'phone_mobile',
				'timezone',
			])
			->seeJsonSubset([
				'id'         => $user->id,
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'email'      => $user->email,
			]);
	}

	/**
	 * Tests Edit Account Profile API end point.
	 * EndPoint: /api/account/edit
	 */
	public function testPostEdit()
	{
		// Create user for testing.
		$api_token = 'testToken123';
		$user = factory(App\User::class)->create([
			'api_token' => $api_token,
		]);

		// Test authorized access now.
		$old_first_name = $user->first_name;
		$old_last_name = $user->last_name;
		$old_email = $user->email;

		$new_first_name = 'NewFirstName';
		$new_last_name = 'NewLastName';
		$new_email = 'NewEmail@com.com';
		$new_password = 'NewPassword';
		$new_password_confirmation = 'NewPassword';

		// Invalid input test.
		$this->actingAs($user)
			->json('POST', '/api/account/edit', [
				'api_token'             => $api_token,
				'last_name'             => $new_last_name,
				'email'                 => $new_email,
				'password'              => $new_password,
				'password_confirmation' => $new_password_confirmation . 'sdf',
			])
			->seeJsonStructure([
				'first_name',
				'password',
			])
			->assertResponseStatus(422);

		// Valid input test.
		$this->actingAs($user)
			->json('POST', '/api/account/edit', [
				'api_token'             => $api_token,
				'first_name'            => $new_first_name,
				'last_name'             => $new_last_name,
				'email'                 => $new_email,
				'password'              => $new_password,
				'password_confirmation' => $new_password_confirmation,
			])
			->assertResponseStatus(200);

		// Test old user does not exist in DB.
		$this->notSeeInDatabase('users', [
			'email'      => $old_email,
			'first_name' => $old_first_name,
			'last_name'  => $old_last_name,
		]);

		// Test new data is in DB.
		$this->seeInDatabase('users', [
			'email'      => $new_email,
			'first_name' => $new_first_name,
			'last_name'  => $new_last_name,
		]);
	}

	/**
	 * Tests post add device
	 * Post: /api/account/add-device
	 */
	public function testPostAddDevice()
	{
		// Create user for testing.
		$api_token = 'testToken123';
		$user = factory(App\User::class)->create([
			'api_token' => $api_token,
		]);

		// Test post add device
		$result = $this->actingAs($user)
			->call('POST', '/api/account/add-device', [
				'api_token' => $api_token,
				'name'      => 'TestDeviceName',
				'token'     => 'TestDeviceToken',
			]);
		$this->assertEquals(200, $result->getStatusCode());

		// Check DB for device insertion.
		$this->seeInDatabase('devices', [
			'user_id' => $user->id,
			'name'    => 'TestDeviceName',
			'token'   => 'TestDeviceToken',
		]);
	}

}
