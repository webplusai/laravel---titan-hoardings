<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HardcodedFormTables extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
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

		Schema::create('qc_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned()->unique();

			// Visual Presentation
			$table->char('good_condition', 2);
			$table->char('hoardings_aligned', 2);
			$table->char('good_paint', 2);
			$table->char('well_lubed', 2);
			$table->char('taped', 2);
			$table->char('dust_supression_installed', 2);
			$table->char('anti_tamper_installed', 2);
			$table->char('do_not_lean_installed', 2);
			$table->char('fingerprints_removed', 2);

			// Housekeeping
			$table->char('floor_swept', 2);
			$table->char('waste_removed', 2);

			// Site Specification
			$table->enum('hoarding_type', ['Kiosk', 'Impact', 'Wind Rated']);
			$table->char('installed_per_plan', 2);
			$table->integer('set_out');

			// Engineer's Specification
			$table->char('uprights_installed', 2);
			$table->enum('stud_spec', ['70x45mm MGP12', 'TITAN Eco']);
			$table->char('double_stud', 2);
			$table->enum('panel_installed', ['12mm MDF', '16mm WB', '18mm ply', '50mm EPS']);
			$table->enum('screw_size', ['8Gx40mm', '8Gx16mm', '12Gx75mm or 14Gx75mm']);
			$table->enum('panel_fixing', ['Pine stud screw', 'TITAN Eco screw']);
			$table->integer('counterweights_quantity');
			$table->decimal('counterweights_height', 3, 1);
			$table->char('wind_compliant', 2);
			$table->char('returns', 2);
			$table->char('bracing', 2);
			$table->char('certificate', 2);

			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
		});

		DB::statement('INSERT INTO jsra_forms (job_id) SELECT id FROM jobs');
		DB::statement('INSERT INTO qc_forms (job_id) SELECT id FROM jobs');

		Schema::drop('form_answers');
		Schema::drop('form_questions');
		Schema::drop('form_categories');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jsra_forms');
		Schema::drop('qc_forms');

		Schema::create('form_categories', function (Blueprint $table) {
			$table->increments('id');
			$table->char('form', 4);
			$table->string('name');
			$table->integer('num');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('form_questions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('category_id')->unsigned();
			$table->integer('num')->unsigned();
			$table->string('question');
			$table->enum('type', ['boolean', 'boolean-null', 'text', 'checkboxes']);
			$table->text('options');
			$table->datetime('created_at');
			$table->datetime('updated_at');
			$table->datetime('deleted_at')->nullable();

			$table->foreign('category_id')->references('id')->on('form_categories')->onDelete('cascade');
		});

		Schema::create('form_answers', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned();
			$table->integer('question_id')->unsigned();
			$table->text('answers');
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
			$table->foreign('question_id')->references('id')->on('form_questions')->onDelete('cascade');
		});
	}

}
