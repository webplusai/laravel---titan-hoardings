<?php

use Illuminate\Database\Seeder;

class MaterialsSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('hoarding_materials')->insert([
			'name' => '12mm MDF'
		]);
		DB::table('hoarding_materials')->insert([
			'name' => '16mm W/Board'
		]);
		DB::table('hoarding_materials')->insert([
			'name' => 'Insulated Panel'
		]);
		DB::table('hoarding_materials')->insert([
			'name' => 'Fence Panels'
		]);
	}

}
