<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Device;
use App\PushNotificationQueue;
use App\Job;
use Carbon\Carbon;
use Mail;

class User extends Authenticatable
{

	protected $guarded = [];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function isGlobalAdmin()
	{
	return $this->type == 'global-admin';
	}

	public function isRootAgentAdmin()
	{
	return $this->type == 'agent-admin' && !$this->agent->parent_agent_id;
	}

	public function isAgentAdmin()
	{
	return $this->type == 'agent-admin';
	}

	public function isAgentUser()
	{
	return $this->type == 'agent-user';
	}

	public function isInstaller()
	{
	return $this->type == 'installer';
	}

	public function canManageJob($job)
	{
		if ($job->agent_id != $this->agent_id) {
			return false;
		}

		if ($this->isGlobalAdmin()) return true;
		if ($this->isAgentAdmin()) return true;
		if ($this->isAgentUser()) return true;
		if ($job->primary_installer_id == $this->id) return true;

		return false;
	}

	public function canManageQuote($quote)
	{
		if ($quote->agent_id != $this->agent_id) {
			return false;
		}

		if ($this->isGlobalAdmin()) return true;
		if ($this->isAgentAdmin()) return true;
		if ($this->isAgentUser()) return true;

		return false;
	}

	public function canCreateQuote()
	{
		if ($this->isGlobalAdmin()) return true;
		if ($this->isAgentAdmin()) return true;
		if ($this->isAgentUser()) return true;

		return false;
	}

	public function canSetPrimaryInstallers()
	{
		return !$this->isInstaller();
	}

	public function sendInvitation()
	{
		$data = [
			'agent' => $this->agent,
			'user'  => $this,
		];

		Mail::send('emails.welcome', $data, function ($mail) {
			$mail->from(env('MAIL_FROM'));
			$mail->to($this->email);
			$mail->subject('Invitation to join Titan Hoardings portal');
		});
	}

	/**
	 * Get single agent (for global-admins, agent-admins and agent-users).
	 */
	public function agent()
	{
		return $this->belongsTo('App\Agent');
	}

	/**
	 * Get jobs for an installer.
	 */
	public function jobs()
	{
		return $this->belongsToMany('App\Job', 'job_installers', 'installer_id');
	}

	public function invitations()
	{
		return $this->hasMany('App\Invitation');
	}

	public function images()
	{
		return $this->hasMany('App\Image');
	}

	/**
	 * Get devices associated with the user.
	 */
	public function devices()
	{
		return $this->hasMany('App\Device');
	}

	/**
	 * Get the user's profile as a url.
	 *
	 * @return string
	 */
	public function getProfilePicture()
	{
		if ($this->profile_image != '') {
			return url('/images/profile_images') . '/' . $this->profile_image;
		}
	}

	/**
	 * Get the user's profile as a url.
	 *
	 * @return string
	 */
	public function getProfilePictureThumbnail()
	{
		if ($this->profile_image != '') {
			$name = explode('.', $this->profile_image);
			return url('/images/profile_images') . '/' . $name[0].'-thumbnail.'.$name[1];
		}
	}

	public function queuePushNotification($alert, $badge, $link_url)
	{
		foreach ($this->devices as $device) {
			dispatch(new \App\Jobs\SendPushNotification($device, $alert, $badge, $link_url));
		}
	}

	public function jobNotification()
	{
		return $this->hasMany('App\JobNotification');
	}

	public function getDashboardUrl()
	{
		$url = '';

		switch ($this->type) {
			case 'global-admin':
				$url = '/admin/dashboard';
				break;
			case 'agent-admin':
				$url = '/dashboard';
				break;
			case 'agent-user':
				$url = '/dashboard';
				break;
			case 'installer':
				$url = '/installer/dashboard';
				break;
			default:
				break;
		}

		return $url;
	}

}
