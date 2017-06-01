<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormBuilder extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_questions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('num')->unsigned();
			$table->string('question', 255);
			$table->enum('type', ['boolean','text','checkboxes']);
			$table->text('options');
			$table->datetime('created_at');
			$table->datetime('updated_at');
			$table->datetime('deleted_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('form_questions');
	}

}
