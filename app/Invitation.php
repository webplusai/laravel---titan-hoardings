<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;

class Invitation extends Model
{

	public $guarded = [];

	/**
	 * Emails the invitation to the user.
	 */
	public function send()
	{
		$user = $this->user;

		$data = [
			'invitation' => $this,
			'user'       => $user,
		];

		Mail::send('emails.invitations-email', $data, function ($mail) use ($user) {
			$mail->from(env('MAIL_FROM'));
			$mail->to($user->email);
			$mail->subject('Invitation to join Titan Hoardings Portal');
		});
	}

	/**
	 * Get the user record associated with the invitation.
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
