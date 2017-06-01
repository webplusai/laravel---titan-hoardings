<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function (Blueprint $table) {
			$table->integer('height_of_panel')->after('name');
			$table->integer('height_of_dust_supression')->after('height_of_panel');
			$table->integer('width')->after('height_of_dust_supression');
			$table->integer('depth')->after('width');
			$table->integer('weight')->after('depth');
			$table->integer('wind_rating')->after('weight');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function (Blueprint $table) {
			$table->dropColumn('height_of_panel');
			$table->dropColumn('height_of_dust_supression');
			$table->dropColumn('width');
			$table->dropColumn('depth');
			$table->dropColumn('weight');
			$table->dropColumn('wind_rating');
		});
	}

}
