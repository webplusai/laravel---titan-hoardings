<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailedQueueTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('failed_queue', function (Blueprint $table) {
			$table->increments('id');
			$table->text('connection');
			$table->text('queue');
			$table->longText('payload');
			$table->timestamp('failed_at')->useCurrent();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('failed_queue');
	}

}
