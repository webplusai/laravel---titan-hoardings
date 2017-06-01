<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPushNotificationQueue extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('push_notification_queue');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('push_notification_queue', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('device_id')->unsigned();
			$table->string('alert');
			$table->string('link_url');
			$table->integer('badge');
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
		});
	}

}
