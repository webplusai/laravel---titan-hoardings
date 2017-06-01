<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobContactsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_contacts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned()->index();
			$table->integer('contact_id')->unsigned()->index();
			$table->unique(['job_id','contact_id']);

			$table->foreign('job_id')->references('id')->on('jobs');
			$table->foreign('contact_id')->references('id')->on('contacts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('job_contacts');
	}

}
