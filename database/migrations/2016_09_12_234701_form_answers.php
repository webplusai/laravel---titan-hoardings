<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormAnswers extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_answers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned()->index();
			$table->integer('question_id')->unsigned();
			$table->text('answers');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('jobs', function(Blueprint $table) {
			$table->datetime('form_completed_at')->nullable()->after('start_time');
		});

		Schema::table('job_installers', function(Blueprint $table) {
			$table->datetime('form_signed_at')->nullable()->after('installer_id');
		});

		Schema::table('users', function(Blueprint $table) {
			$table->string('timezone', 255)->default('Australia/Brisbane')->after('remember_token');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('form_answers');

		Schema::table('jobs', function(Blueprint $table) {
			$table->dropColumn('form_completed_at');
		});

		Schema::table('job_installers', function(Blueprint $table) {
			$table->dropColumn('form_signed_at');
		});

		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('timezone');
		});
	}

}
