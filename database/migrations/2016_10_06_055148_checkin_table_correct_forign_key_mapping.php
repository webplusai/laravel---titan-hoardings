<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CheckinTableCorrectForignKeyMapping extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('check_ins', function (Blueprint $table) {
			$table->dropForeign('check_ins_installer_id_foreign');
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
		Schema::table('check_ins', function (Blueprint $table) {
			$table->dropForeign('check_ins_installer_id_foreign');
			$table->foreign('installer_id')->references('id')->on('installers');
		});
	}

}
