<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyJsraFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		// Drop original table
		Schema::drop('jsra_forms');

		// Create a new schema
		Schema::create('jsra_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned()->unique();
			$table->char('manual_handling', 2);
			$table->boolean('multi_person_lift');
			$table->boolean('trolleys_for_transport');
			$table->boolean('lift_one_at_time');
			$table->boolean('job_rotation_breaks');
			$table->char('has_public_access', 2);
			$table->boolean('exclusion_zone');
			$table->boolean('awareness_of_people');
			$table->char('is_above_2_metres', 2);
			$table->boolean('platform_ladder');
			$table->boolean('ewp');
			$table->boolean('mobile_scaffold');
			$table->char('hazardous_material', 2);
			$table->boolean('vacuum_dust');
			$table->boolean('respiratory_eye_hearing_ppe');
			$table->char('has_potential_falling_objects', 2);
			$table->boolean('two_persons');
			$table->boolean('wear_hardhat');
			$table->char('wear_appropriate_ppe', 2);
			$table->boolean('ppe_boots');
			$table->boolean('ppe_shirt');
			$table->boolean('ppe_eye_protection');
			$table->boolean('ppe_ears');
			$table->boolean('ppe_gloves');
			$table->string('has_other_hazards', 2);
			$table->string('other_hazards');
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
		});

		DB::statement('INSERT INTO jsra_forms (job_id) SELECT id FROM jobs');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop new table
		Schema::drop('jsra_forms');

		// Add original table
		Schema::create('jsra_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned()->index()->unique();
			$table->char('is_above_2_metres', 2);
			$table->boolean('ladder');
			$table->boolean('ewp');
			$table->boolean('scaffolding');
			$table->char('has_public_access', 2);
			$table->char('hazardous_material', 2);
			$table->char('manual_handling', 2);
			$table->char('has_current_test_tag', 2);
			$table->boolean('ppe_boots');
			$table->boolean('ppe_shirt');
			$table->boolean('ppe_gloves');
			$table->boolean('ppe_eye_protection');
			$table->string('ppe_other');
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
		});

		DB::statement('INSERT INTO jsra_forms (job_id) SELECT id FROM jobs');
	}

}