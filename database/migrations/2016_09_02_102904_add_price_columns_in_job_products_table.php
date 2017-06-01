<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceColumnsInJobProductsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('job_products', function (Blueprint $table) {
			$table->decimal('price', 5, 2)->after('product_id');
			$table->integer('quantity')->after('price');
			$table->tinyInteger('is_collected')->after('price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('job_products', function (Blueprint $table) {
			$table->dropColumn('price');
			$table->dropColumn('quantity');
			$table->dropColumn('is_collected');
		});
	}

}
