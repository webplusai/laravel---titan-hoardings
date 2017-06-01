<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusesToJobsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE jobs CHANGE COLUMN status status ENUM('pending', 'accepted', 'jsra-pending', 'jsra-complete', 'site-photos-taken', 'in-progress', 'works-completed', 'installation-certificate-complete', 'qc-pending', 'pending-review', 'complete')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE jobs CHANGE COLUMN status status ENUM('pending','installation', 'documentation','complete')");
	}

}
