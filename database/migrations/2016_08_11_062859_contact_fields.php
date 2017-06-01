<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contacts', function(Blueprint $table) {
			$table->dropColumn('client_id');
		});

		Schema::table('contacts', function(Blueprint $table) {
			$table->integer('agent_id')->unsigned()->after('id')->index();
			$table->integer('client_id')->unsigned()->after('agent_id')->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contacts', function(Blueprint $table) {
			$table->dropColumn('client_id');
			$table->dropColumn('agent_id');
		});

		Schema::table('contacts', function(Blueprint $table) {
			$table->integer('client_id')->after('last_name');
		});
	}

}
