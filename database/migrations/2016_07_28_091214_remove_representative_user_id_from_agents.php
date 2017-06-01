<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRepresentativeUserIdFromAgents extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('agents', function (Blueprint $table) {
			$table->dropColumn('representative_user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('agents', function (Blueprint $table) {
			$table->integer('representative_user_id')->unsigned()->after('id');
		});
	}

}
