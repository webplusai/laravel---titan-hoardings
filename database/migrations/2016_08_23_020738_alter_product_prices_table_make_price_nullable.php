<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductPricesTableMakePriceNullable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_prices', function (Blueprint $table) {
			$table->decimal('price', 5, 2)->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_prices', function (Blueprint $table) {
			$table->decimal('price', 5, 2)->change();
		});
	}

}
