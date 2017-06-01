<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quotes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned();
			$table->integer('client_id')->unsigned();
			$table->integer('hoarding_type_id')->unsigned();
			$table->integer('payment_terms')->unsigned()->nullable();
			$table->string('first_name');
			$table->string('last_name');
			$table->string('position');
			$table->string('address');
			$table->string('suburb');
			$table->char('state', 3);
			$table->char('postcode', 4);
			$table->string('email');
			$table->text('description');
			$table->string('tenancy_name');
			$table->decimal('return_size', 3, 2);
			$table->decimal('panel_height', 3, 2);
			$table->decimal('travel_charge', 3, 2);
			$table->decimal('lineal_meters', 3, 2);
			$table->decimal('cost', 6, 2);
			$table->enum('status', ['draft', 'quoted', 'accepted', 'expired']);
			$table->date('quote_date');
			$table->dateTime('expires_at');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('agent_id')->references('id')->on('agents');
			$table->foreign('client_id')->references('id')->on('clients');
			$table->foreign('hoarding_type_id')->references('id')->on('hoarding_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quotes');
	}

}
