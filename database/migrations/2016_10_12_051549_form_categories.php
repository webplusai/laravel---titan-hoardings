<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormCategories extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_categories', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('num');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('form_questions', function(Blueprint $table) {
			$table->integer('category_id')->unsigned()->index()->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('form_categories');

		Schema::table('form_questions', function(Blueprint $table) {
			$table->dropColumn('category_id');
		});
	}

}
