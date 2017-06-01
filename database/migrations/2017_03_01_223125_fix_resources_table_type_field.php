<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixResourcesTableTypeField extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resources', function (Blueprint $table) {
			$table->dropColumn('type');
		});
		Schema::table('resources', function (Blueprint $table) {
			$table->enum('type', ['file', 'image', 'video'])->after('description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resources', function (Blueprint $table) {
			$table->dropColumn('type');
		});
		Schema::table('resources', function (Blueprint $table) {
			$table->enum('type', ['pdf', 'image', 'video'])->after('description');
		});
	}
}
