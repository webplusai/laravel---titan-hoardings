<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckInsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('check_ins', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('installer_id')->unsigned();
			$table->integer('job_id')->unsigned();
			$table->datetime('check_in_time');
			$table->datetime('check_out_time')->nullable();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('installer_id')->references('id')->on('installers');
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
		Schema::drop('check_ins');
	}

}
