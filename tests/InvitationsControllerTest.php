<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvitationsControllerTest extends TestCase
{
	// Invitation Controller test

	/**
	 * Test get Accept
	 * GET: /invitations/accept
	 * POST: /invitations/accept
	 */
	public function testAcceptInvitation()
	{
		// Test get with invalid token
		$this->get('/invitations/accept/' . str_random(6))
			->assertResponseStatus(404);

		// Test post with invalid token
		$this->post('/invitations/accept/' . str_random(6))
			->assertResponseStatus(404);

		// Create valid invitation
		$user = factory(App\User::class, 'installer')->create();

		$invitation = new App\Invitation;
		$invitation->token = substr(md5(microtime()), 0, 10);
		$invitation->user_id = $user->id;
		$invitation->save();

		// Test get, post with valid data
		$this->visit('/invitations/accept/' . $invitation->token)
			->assertResponseStatus(200)
			->type('NewPassword', 'password')
			->type('NewPassword', 'password_confirmation')
			->check('policy')
			->press('Finish Setup');

		// Check DB for invitation delete and new password.
		$this->dontSeeInDatabase('invitations', [
			'user_id' => $user->id,
			'token'   => $invitation->token,
		]);

		$user_accepted = App\User::find($user->id);
		$this->assertNotNull($user_accepted->password);
	}

}
