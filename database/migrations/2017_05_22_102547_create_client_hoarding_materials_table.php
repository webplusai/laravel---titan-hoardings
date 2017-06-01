<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientHoardingMaterialsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::create('client_hoarding_materials', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('client_id')->unsigned();
			$table->integer('hoarding_material_id')->unsigned();

			$table->unique(['client_id', 'hoarding_material_id']);
			$table->foreign('client_id')->references('id')->on('clients');
			$table->foreign('hoarding_material_id')->references('id')->on('hoarding_materials');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('client_hoarding_materials');
	}

}
