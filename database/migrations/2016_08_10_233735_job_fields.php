<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('jobs');

		Schema::create('jobs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned()->index();
			$table->integer('client_id')->unsigned()->index();
			$table->integer('user_id')->unsigned();
			$table->integer('hoarding_type_id')->unsigned();
			$table->integer('material_id')->unsigned();

			$table->enum('type', ['installation','modification','removal']);
			$table->enum('status', ['pending','installation','documentation','complete']);

			$table->string('shop_name', 255);
			$table->string('address', 255);
			$table->string('suburb', 255);
			$table->enum('state', ['ACT','NSW','NT','QLD','SA','TAS','VIC','WA']);
			$table->string('postcode', 4);

			$table->integer('num_doors')->unsigned();
			$table->integer('dust_panel_height')->unsigned();
			$table->decimal('total_length', 6, 2)->unsigned();
			$table->decimal('total_height', 6, 2)->unsigned();

			$table->string('hoarding_type_other', 255);
			$table->string('material_other', 255);

			$table->datetime('start_time')->nullable();
			$table->datetime('finish_time')->nullable();
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobs');

		Schema::create('jobs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned()->index();
			$table->integer('client_id')->unsigned()->index();
			$table->integer('user_id')->unsigned();
			$table->string('name', 255);
			$table->string('hoarding_door_quantity', 255);
			$table->string('hoarding_dust_suppression', 255);
			$table->string('hoarding_height', 255);
			$table->string('hoarding_length', 255);
			$table->integer('hoarding_material_id');
			$table->integer('hoarding_type_id');
			$table->enum('status', ['Pending','Installation','Documentation','Complete']);
			$table->string('tenancy', 255);
			$table->string('location', 255);
			$table->datetime('date_finish');
			$table->datetime('date_start');
			$table->enum('type', ['Installation','Modification','Removal']);
			$table->string('state', 255);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});
	}

}
