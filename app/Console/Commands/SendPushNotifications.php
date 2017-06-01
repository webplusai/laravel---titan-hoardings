<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PushNotificationQueue;
use App\Device;
use Edujugon\PushNotification\PushNotification;

class SendPushNotifications extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'send-push-notifications';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sends Push Notifications.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		while ($item = PushNotificationQueue::first()) {
			$this->sendNotification($item);
			$item->delete();
		}
	}

	private function sendNotification($notification)
	{
		$device = Device::findOrFail($notification->device_id);

		$push = new PushNotification('apn');
		$push->setMessage([
			'aps' => [
				'alert' => [
					'body' => $notification->alert,
				],
				'badge'    => $notification->badge,
				'link_url' => $notification->link_url,
			],
		]);

		$push->setDevicesToken([$device->token]);
		$push->send();
	}

}
