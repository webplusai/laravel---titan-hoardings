<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReturnSizeJobs extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobs', function (Blueprint $table) {
			$table->decimal('return_size', 6, 2)->unsigned()->after('total_height');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobs', function (Blueprint $table) {
			$table->dropColumn('return_size');
		});
	}

}
