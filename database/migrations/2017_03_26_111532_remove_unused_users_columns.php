<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedUsersColumns extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('profile_image');
			$table->dropColumn('abn');
			$table->dropColumn('billing_address');
			$table->dropColumn('shipping_address');
			$table->dropColumn('phone_main');
			$table->dropColumn('phone_mobile');
			$table->dropColumn('fax');
			$table->dropColumn('email_billing');
			$table->dropColumn('bank_account_name');
			$table->dropColumn('bank_account_number');
			$table->dropColumn('banking_bsb');
			$table->dropColumn('date_of_birth');
			$table->dropColumn('gender');
			$table->dropColumn('certification');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->string('profile_image')->after('email');
			$table->string('abn', 40)->after('email');
			$table->string('billing_address', 250)->after('abn');
			$table->string('shipping_address', 250)->after('billing_address');
			$table->string('phone_main', 10)->after('shipping_address');
			$table->string('phone_mobile', 10)->after('phone_main');
			$table->string('fax', 10)->after('phone_mobile');
			$table->string('email_billing', 50)->unique()->after('fax');
			$table->string('bank_account_name', 50)->after('email_billing');
			$table->string('bank_account_number')->after('bank_account_name');
			$table->string('banking_bsb', 40)->after('bank_account_number');
			$table->date('date_of_birth')->after('banking_bsb');
			$table->string('gender', 30)->after('date_of_birth');
			$table->boolean('certification')->after('gender');
		});
	}

}
