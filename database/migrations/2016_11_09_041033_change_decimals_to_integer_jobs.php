<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecimalsToIntegerJobs extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('UPDATE jobs SET dust_panel_height=dust_panel_height*1000');
		DB::statement('UPDATE jobs SET total_length=total_length*1000');
		DB::statement('UPDATE jobs SET total_height=total_height*1000');
		DB::statement('UPDATE jobs SET return_size=return_size*1000');

		DB::statement('ALTER TABLE jobs CHANGE total_length total_length INTEGER(10) UNSIGNED');
		DB::statement('ALTER TABLE jobs CHANGE total_height total_height INTEGER(10) UNSIGNED');
		DB::statement('ALTER TABLE jobs CHANGE return_size return_size INTEGER(10) UNSIGNED');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE jobs CHANGE total_length total_length DECIMAL(6,2) UNSIGNED');
		DB::statement('ALTER TABLE jobs CHANGE total_height total_height DECIMAL(6,2) UNSIGNED');
		DB::statement('ALTER TABLE jobs CHANGE return_size return_size DECIMAL(6,2) UNSIGNED');

		DB::statement('UPDATE jobs SET dust_panel_height=dust_panel_height/1000');
		DB::statement('UPDATE jobs SET total_length=total_length/1000');
		DB::statement('UPDATE jobs SET total_height=total_height/1000');
		DB::statement('UPDATE jobs SET return_size=return_size/1000');
	}

}
