<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobNotesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_notes', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned()->references('id')->on('jobs')->index();
			$table->integer('user_id')->unsigned()->references('id')->on('users')->index();
			$table->text('message');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('job_notes');
	}
}