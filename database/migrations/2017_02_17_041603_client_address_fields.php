<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientAddressFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('clients', function (Blueprint $table) {
			$table->string('billing_suburb')->after('billing_address');
			$table->string('billing_state', 3)->after('billing_suburb');
			$table->string('billing_postcode', 4)->after('billing_state');
			$table->string('shipping_suburb')->after('shipping_address');
			$table->string('shipping_state', 3)->after('shipping_suburb');
			$table->string('shipping_postcode', 4)->after('shipping_state');
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
			$table->dropColumn('billing_suburb');
			$table->dropColumn('billing_state');
			$table->dropColumn('billing_postcode');
			$table->dropColumn('shipping_suburb');
			$table->dropColumn('shipping_state');
			$table->dropColumn('shipping_postcode');
		});
	}

}
