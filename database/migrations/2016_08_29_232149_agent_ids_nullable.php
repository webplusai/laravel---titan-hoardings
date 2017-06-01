<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgentIdsNullable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE agents CHANGE parent_agent_id parent_agent_id INT(10) UNSIGNED DEFAULT NULL');
		DB::statement('ALTER TABLE users CHANGE agent_id agent_id INT(10) UNSIGNED DEFAULT NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE agents CHANGE parent_agent_id parent_agent_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE users CHANGE agent_id agent_id INT(10) UNSIGNED NOT NULL');
	}

}
