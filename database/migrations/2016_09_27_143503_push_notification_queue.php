<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PushNotificationQueue extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('push_notification_queue', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('device_id')->unsigned()->index();
			$table->string('alert', 255);
			$table->string('link_url', 255);
			$table->integer('badge');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('device_id')->references('id')->on('devices');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	   Schema::drop('push_notification_queue');
	}

}
