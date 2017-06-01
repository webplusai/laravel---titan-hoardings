<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToJobs extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobs', function (Blueprint $table) {
			$table->string('state')->after('name');
			$table->enum('type', ['Installation','Modification','Removal'])->after('name');
			$table->dateTime('date_start')->after('name');
			$table->dateTime('date_finish')->after('name');
			$table->string('location')->after('name');
			$table->string('tenancy')->after('name');
			$table->enum('status', ['Pending','Installation','Documentation','Complete'])->after('name');
			$table->integer('hoarding_type_id')->after('name');
			$table->integer('hoarding_material_id')->after('name');
			$table->string('hoarding_length')->after('name');
			$table->string('hoarding_height')->after('name');
			$table->string('hoarding_dust_suppression')->after('name');
			$table->string('hoarding_door_quantity')->after('name');
		});

		Schema::create('hoarding_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
		});

		Schema::create('hoarding_materials', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
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
			$table->dropColumn('state');
			$table->dropColumn('type');
			$table->dropColumn('date_start');
			$table->dropColumn('date_finish');
			$table->dropColumn('location');
			$table->dropColumn('tenancy');
			$table->dropColumn('status');
			$table->dropColumn('hoarding_type_id');
			$table->dropColumn('hoarding_material_id');
			$table->dropColumn('hoarding_length');
			$table->dropColumn('hoarding_height');
			$table->dropColumn('hoarding_dust_suppression');
			$table->dropColumn('hoarding_door_quantity');
		});

		Schema::drop('hoarding_types');
		Schema::drop('hoarding_materials');
	}

}
