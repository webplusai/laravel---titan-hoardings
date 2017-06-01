<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColToJobInstaller extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('job_installers', function (Blueprint $table) {
			$table->enum('status', ['pending', 'accepted', 'declined'])->after('form_signed_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('job_installers', function (Blueprint $table) {
			$table->dropColumn('status');
		});
	}

}
