<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyJobInstallers extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('job_installers');
		Schema::create('job_installers', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned();
			$table->integer('installer_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['job_id','installer_id']);
			$table->foreign('job_id')->references('id')->on('jobs');
			$table->foreign('installer_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('job_installers');
		Schema::create('job_installers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned();
			$table->integer('installer_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['job_id','installer_id']);
		});
	}

}
