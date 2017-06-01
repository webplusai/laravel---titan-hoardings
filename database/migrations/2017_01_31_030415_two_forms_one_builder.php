<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TwoFormsOneBuilder extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('form_categories', function (Blueprint $table) {
			$table->char('form', 4)->after('id');
		});

		DB::statement("UPDATE form_categories SET form = 'jsra'");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('form_categories', function (Blueprint $table) {
			$table->dropColumn('form');
		});
	}

}
