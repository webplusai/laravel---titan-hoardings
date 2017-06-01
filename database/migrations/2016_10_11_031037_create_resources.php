<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResources extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resources', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->enum('type', ['pdf','image','video']);
			$table->string('url');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('resource_views', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('resource_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->datetime('last_viewed_at');
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['resource_id','user_id']);
			$table->index('user_id');
		});

		Schema::create('product_resources', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->integer('resource_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['product_id','resource_id']);
			$table->index('resource_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources');
		Schema::drop('resource_views');
		Schema::drop('product_resources');
	}
}
