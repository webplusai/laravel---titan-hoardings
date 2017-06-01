<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstallersRestructure extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('installers'); // unused table

		Schema::create('agent_installers', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned();
			$table->integer('installer_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->unique(['agent_id', 'installer_id']);
			$table->index('installer_id');
		});

		DB::statement("INSERT INTO agent_installers (agent_id, installer_id) SELECT agent_id, id FROM users WHERE type = 'installer'");
		DB::statement("UPDATE users SET agent_id = NULL where type = 'installer'");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('installers', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned()->index();
			$table->string('name');
			$table->date('dob');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		DB::statement("UPDATE users u INNER JOIN agent_installers ai ON u.id = ai.installer_id SET u.agent_id = ai.agent_id WHERE u.type = 'installer'");

		Schema::drop('agent_installers');
	}

}
