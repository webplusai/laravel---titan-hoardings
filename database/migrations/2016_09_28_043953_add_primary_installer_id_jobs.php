<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryInstallerIdJobs extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobs', function (Blueprint $table) {
			$table->integer('primary_installer_id')->after('user_id')->unsigned()->nullable();

			$table->foreign('primary_installer_id')->references('id')->on('users');
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
			$table->dropForeign('jobs_primary_installer_id_foreign');
			$table->dropColumn('primary_installer_id');
		});
	}

}
