<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToContactsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contacts', function (Blueprint $table) {
			$table->dropColumn('name');

			$table->string('first_name', 50)->after('id');
			$table->string('last_name', 50)->after('first_name');
			$table->string('position', 50)->after('phone');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contacts', function (Blueprint $table) {
			$table->dropColumn('first_name');
			$table->dropColumn('last_name');
			$table->dropColumn('position');

			$table->string('name');
		});
	}

}
