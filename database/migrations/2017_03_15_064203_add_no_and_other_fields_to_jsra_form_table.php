<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoAndOtherFieldsToJsraFormTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jsra_forms', function(Blueprint $table) {
			$table->string('manual_handling_no_reason')->after('manual_handling');
			$table->string('manual_handling_other')->after('manual_handling_no_reason');
			$table->string('has_public_access_no_reason')->after('has_public_access');
			$table->string('has_public_access_other')->after('has_public_access_no_reason');
			$table->string('hazardous_material_no_reason')->after('hazardous_material');
			$table->string('hazardous_material_other')->after('hazardous_material_no_reason');
			$table->string('has_potential_falling_objects_no_reason')->after('has_potential_falling_objects');
			$table->string('has_potential_falling_objects_other')->after('has_potential_falling_objects_no_reason');
			$table->string('wear_appropriate_ppe_no_reason')->after('wear_appropriate_ppe');
			$table->string('wear_appropriate_ppe_other')->after('wear_appropriate_ppe_no_reason');
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
			$table->dropColumn('manual_handling_no_reason');
			$table->dropColumn('manual_handling_other');
			$table->dropColumn('has_public_access_no_reason');
			$table->dropColumn('has_public_access_other');
			$table->dropColumn('hazardous_material_no_reason');
			$table->dropColumn('hazardous_material_other');
			$table->dropColumn('has_potential_falling_objects_no_reason');
			$table->dropColumn('has_potential_falling_objects_other');
			$table->dropColumn('wear_appropriate_ppe_no_reason');
			$table->dropColumn('wear_appropriate_ppe_other');
		});
	}

}
