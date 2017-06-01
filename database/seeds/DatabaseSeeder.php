<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
//		$this->call(HoardingTypesSeeder::class);
//		$this->call(MaterialsSeeder::class);
		$this->call(TestUsersSeeder::class);
	}

}
