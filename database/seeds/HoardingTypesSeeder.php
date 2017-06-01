<?php

use Illuminate\Database\Seeder;

class HoardingTypesSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('hoarding_types')->insert([
			'name' => 'Impact Rated'
		]);
		DB::table('hoarding_types')->insert([
			'name' => 'Kiosk'
		]);
		DB::table('hoarding_types')->insert([
			'name' => 'Std Wind Rated'
		]);
		DB::table('hoarding_types')->insert([
			'name' => 'Eco Wind Rated'
		]);
		DB::table('hoarding_types')->insert([
			'name' => 'Temporary Fence'
		]);
	}

}
