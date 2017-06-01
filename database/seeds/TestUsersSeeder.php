<?php

use Illuminate\Database\Seeder;

class TestUsersSeeder extends Seeder
{
	// For testing purpose

	public function run()
	{
//		// Create test agent, agent-admin, client
		$agent = factory(App\Agent::class)->create();
//
//		// To be able to login at web page, specified email and password.
//		$user = factory(App\User::class, 'agent-admin')->create([
//			'email'    => 'testagentadmin@com.com',
//			'password' => bcrypt('tester'),
//			'agent_id' => $agent->id,
//		]);
//
//		// Create test global-admin
//		$global_admin = factory(App\User::class, 'global-admin')->create([
//			'email'    => 'testglobaladmin@com.com',
//			'password' => bcrypt('tester'),
//		]);
//
//		$client = factory(App\Client::class)->create([
//			'name'     => 'TestClient',
//			'agent_id' => $agent->id,
//		]);

		$agent_user = factory(App\User::class, 'agent-user')->create([
			'email'     =>'testagentuser@com.com',
		    'password'  => bcrypt('tester'),
		    'agent_id'  =>  $agent->id
		]);

		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();

		// Associate installer with agent-admin.
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);
	}

}
