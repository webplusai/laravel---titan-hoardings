<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeHoardingTypesAndMaterialTypesNullable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_hoarding_type_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_material_id_foreign');

		DB::statement('ALTER TABLE `jobs` CHANGE `hoarding_type_id` `hoarding_type_id` INT(10) UNSIGNED NULL;');
		DB::statement('ALTER TABLE `jobs` CHANGE `material_id` `material_id` INT(10)  UNSIGNED  NULL;');

		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_hoarding_type_id_foreign FOREIGN KEY (hoarding_type_id) REFERENCES hoarding_types(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_material_id_foreign FOREIGN KEY (material_id) REFERENCES hoarding_materials(id) ON DELETE CASCADE');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_hoarding_type_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_material_id_foreign');

		DB::statement('ALTER TABLE `jobs` CHANGE `hoarding_type_id` `hoarding_type_id` INT(10)  UNSIGNED  NOT NULL');
		DB::statement('ALTER TABLE `jobs` CHANGE `material_id` `material_id` INT(10)  UNSIGNED  NOT NULL;');

		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_hoarding_type_id_foreign FOREIGN KEY (hoarding_type_id) REFERENCES hoarding_types(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_material_id_foreign FOREIGN KEY (material_id) REFERENCES hoarding_materials(id) ON DELETE CASCADE');
	}

}