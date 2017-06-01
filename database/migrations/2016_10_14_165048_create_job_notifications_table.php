<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobNotificationsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_notifications', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('agent_id')->unsigned()->nullable();
			$table->integer('job_id')->unsigned()->nullable();
			$table->text('message');
			$table->text('title');
			$table->enum('type', ['info','system','alert']);
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('agent_id')->references('id')->on('agents');
			$table->foreign('job_id')->references('id')->on('jobs');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('job_notifications');
	}

}
