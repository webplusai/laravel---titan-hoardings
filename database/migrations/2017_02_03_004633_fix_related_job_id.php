<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRelatedJobId extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_related_job_id_foreign');
		DB::statement('ALTER TABLE jobs CHANGE related_job_id related_job_id INT(10) UNSIGNED DEFAULT NULL');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_related_job_id_foreign FOREIGN KEY (related_job_id) REFERENCES jobs(id) ON DELETE SET NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_related_job_id_foreign');
		DB::statement('ALTER TABLE jobs CHANGE related_job_id related_job_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_related_job_id_foreign FOREIGN KEY (related_job_id) REFERENCES jobs(id) ON DELETE CASCADE');
	}

}
