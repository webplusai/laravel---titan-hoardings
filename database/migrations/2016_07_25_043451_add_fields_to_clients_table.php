<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToClientsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('clients', function (Blueprint $table) {
			$table->dropColumn('agent_id');

			$table->integer('representative_user_id')->unsigned()->after('id');
			$table->string('email', 255)->after('name');
			$table->string('billing_email', 255)->after('email');
			$table->string('abn', 11)->after('billing_email');
			$table->string('billing_address', 255)->after('abn');
			$table->string('shipping_address', 255)->after('billing_address');
			$table->string('phone', 10)->after('shipping_address');
			$table->string('mobile', 10)->after('billing_email');
			$table->string('fax', 10)->after('mobile');
			$table->string('size', 10)->after('fax');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('clients', function (Blueprint $table) {
			$table->dropColumn('representative_user_id');
			$table->dropColumn('email');
			$table->dropColumn('billing_email');
			$table->dropColumn('abn');
			$table->dropColumn('billing_address');
			$table->dropColumn('shipping_address');
			$table->dropColumn('phone');
			$table->dropColumn('mobile');
			$table->dropColumn('fax');
			$table->dropColumn('size');

			$table->integer('agent_id');
		});
	}

}
