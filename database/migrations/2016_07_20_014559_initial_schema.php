<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialSchema extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('parent_agent_id')->unsigned()->index();
			$table->integer('representative_user_id')->unsigned();
			$table->string('name', 255);
			$table->string('abn', 11);
			$table->string('billing_address', 255);
			$table->string('billing_suburb', 255);
			$table->string('billing_state', 3);
			$table->string('billing_postcode', 4);
			$table->string('shipping_address', 255);
			$table->string('shipping_suburb', 255);
			$table->string('shipping_state', 3);
			$table->string('shipping_postcode', 4);
			$table->string('phone', 10);
			$table->string('mobile', 10);
			$table->string('fax', 10);
			$table->string('email', 255);
			$table->string('billing_email', 255);
			$table->integer('num_employees')->unsigned();
			$table->string('bank_acc_name');
			$table->string('bank_acc_bsb');
			$table->string('bank_acc_no');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('certifications', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id')->unsigned()->index();
			$table->string('name', 255);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned()->index();
			$table->string('name', 255);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('contacts', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('client_id');
			$table->string('name', 255);
			$table->string('email', 255);
			$table->string('phone', 255);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('documents', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('document_fields', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('document_id')->unsigned()->index();
			$table->string('name', 255);
			$table->string('type', 255);
			$table->text('options');
			$table->integer('sort');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('document_submissions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('document_id')->unsigned();
			$table->integer('job_id')->unsigned()->index();
			$table->integer('user_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('document_submission_values', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('submission_id')->unsigned()->index();
			$table->integer('field_id')->unsigned();
			$table->text('value');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('installers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned()->index();
			$table->string('name', 255);
			$table->date('dob');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('installer_certifications', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('installer_id')->unsigned();
			$table->integer('certification_id')->unsigned()->index();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['installer_id','certification_id']);
		});

		Schema::create('invitations', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('token', 10)->index();
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('jobs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned()->index();
			$table->integer('client_id')->unsigned()->index();
			$table->integer('user_id')->unsigned();
			$table->string('name', 255);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('job_installers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned();
			$table->integer('installer_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['job_id','installer_id']);
		});

		Schema::create('job_products', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['job_id','product_id']);
		});

		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('product_prices', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->integer('agent_id')->unsigned();
			$table->decimal('price', 5, 2);
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['product_id','agent_id']);
		});

		Schema::table('users', function(Blueprint $table) {
			$table->integer('agent_id')->unsigned()->index()->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agents');
		Schema::drop('certifications');
		Schema::drop('clients');
		Schema::drop('contacts');
		Schema::drop('documents');
		Schema::drop('document_fields');
		Schema::drop('document_submissions');
		Schema::drop('document_submission_values');
		Schema::drop('installers');
		Schema::drop('installer_certifications');
		Schema::drop('invitations');
		Schema::drop('jobs');
		Schema::drop('job_installers');
		Schema::drop('job_products');
		Schema::drop('products');
		Schema::drop('product_prices');

		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('agent_id');
		});
	}

}
