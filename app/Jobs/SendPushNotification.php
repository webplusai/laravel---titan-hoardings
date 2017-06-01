<?php

namespace App\Jobs;

use App\Device;
use App\Jobs\Job;
use Edujugon\PushNotification\PushNotification as EdujugonPushNotification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPushNotification extends Job implements ShouldQueue
{
	use InteractsWithQueue, SerializesModels;

	private $device = null;
	private $alert = null;
	private $badge = null;
	private $link_url = null;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Device $device, $alert, $badge, $link_url)
	{
		$this->device = $device;
		$this->alert = $alert;
		$this->badge = $badge;
		$this->link_url = $link_url;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$push = new EdujugonPushNotification('apn');
		$push->setMessage([
			'aps' => [
				'alert' => [
					'body' => $this->alert,
				],
				'badge'    => $this->badge,
				'link_url' => $this->link_url,
			],
		]);

		$push->setDevicesToken([$this->device->token]);
		$push->send();
	}

}
