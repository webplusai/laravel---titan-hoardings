<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesToJobs extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobs', function (Blueprint $table) {
			$table->dropColumn('finish_time');
			$table->integer('related_job_id')->after('user_id')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobs', function (Blueprint $table) {
			$table->dateTime('finish_time');
			$table->dropColumn('related_job_id');
		});
	}

}
