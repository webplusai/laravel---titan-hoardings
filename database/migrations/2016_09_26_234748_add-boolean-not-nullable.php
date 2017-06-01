<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBooleanNotNullable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE form_questions CHANGE type type ENUM('boolean','boolean-null','text','checkboxes') NOT NULL");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE form_questions CHANGE type type ENUM('boolean','text','checkboxes') NOT NULL");
	}

}
