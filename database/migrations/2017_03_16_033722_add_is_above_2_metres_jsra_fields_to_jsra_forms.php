<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAbove2MetresJsraFieldsToJsraForms extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jsra_forms', function(Blueprint $table) {
			$table->string('is_above_2_metres_no_reason')->after('is_above_2_metres');
			$table->string('is_above_2_metres_other')->after('is_above_2_metres_no_reason');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jsra_forms', function(Blueprint $table) {
			$table->dropColumn('is_above_2_metres_no_reason');
			$table->dropColumn('is_above_2_metres_other');
		});
	}

}
