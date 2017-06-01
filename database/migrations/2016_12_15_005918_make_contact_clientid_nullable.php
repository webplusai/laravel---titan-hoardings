<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeContactClientidNullable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE contacts CHANGE client_id client_id INT(10) UNSIGNED DEFAULT NULL');
		DB::statement('UPDATE contacts SET client_id = NULL WHERE client_id = 0');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE contacts CHANGE client_id client_id INT(10) UNSIGNED NOT NULL');
	}

}
