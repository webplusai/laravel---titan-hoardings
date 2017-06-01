<?php

use Illuminate\Support\Facades\DB;

class APIFormControllerTest extends TestCase
{
	// API/FormController test

	/**
	 * Test Get JSRA, post jsra
	 * GET: /api/form/jsra/{job_id}
	 */
	public function testGetJSRA()
	{
		// Create a test job.
		$agent = factory(App\Agent::class)->create();

		$user = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);

		$client = factory(App\Client::class)->create([
			'agent_id' => $agent->id,
		]);

		$job = factory(App\Job::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
		]);

		$job->jsra()->create([]);
		$job->qc()->create([]);

		// Ensure job is created.
		$this->seeInDatabase('jobs', [
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
		]);

		// Create test installer.
		$api_token = 'testToken123';
		$installer = factory(App\User::class, 'installer')->create([
			'api_token' => $api_token,
		]);

		// Associate installer with agent-admin.
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Associate installer with job, make primary installer.
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);
		$job->primary_installer_id = $installer->id;
		$job->save();

		// Test get JSRA form.
		$this->actingAs($installer)
			->json('GET', '/api/form/jsra/' . $job->id, [
				'api_token' => $api_token,
			])
			->assertResponseStatus(200);

		$test_jsra = [
			'manual_handling'               => 'Y',
			'multi_person_lift'             => 1,
			'trolleys_for_transport'        => 1,
			'lift_one_at_time'              => 1,
			'job_rotation_breaks'           => 1,
			'has_public_access'             => 'N',
			'exclusion_zone'                => 1,
			'awareness_of_people'           => 1,
			'is_above_2_metres'             => 'N',
			'platform_ladder'               => 1,
			'ewp'                           => 1,
			'mobile_scaffold'               => 1,
			'hazardous_material'            => 'Y',
			'vacuum_dust'                   => 1,
			'respiratory_eye_hearing_ppe'   => 1,
			'has_potential_falling_objects' => 'N',
			'two_persons'                   => 1,
			'wear_hardhat'                  => 1,
			'wear_appropriate_ppe'          => 'Y',
			'ppe_boots'                     => 1,
			'ppe_shirt'                     => 1,
			'ppe_eye_protection'            => 1,
			'ppe_ears'                      => 1,
			'ppe_gloves'                    => 1,
			'has_other_hazards'             => 'Y',
			'other_hazards'                 => 'Test hazards',
		];

		// Test post JSRA form.
		$this->actingAs($installer)
			->json('POST', '/api/form/jsra/' . $job->id, $test_jsra)
			->assertResponseStatus(200);

		// See db has successfully changed.
		$this->seeInDatabase('jsra_forms', $test_jsra);
	}

	/**
	 * Test Get QC, post QC
	 * GET: /api/form/qc/{job_id}
	 */
	public function testGetQC()
	{
		// Create a test job.
		$agent = factory(App\Agent::class)->create();

		$user = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);

		$client = factory(App\Client::class)->create([
			'agent_id' => $agent->id,
		]);

		$job = factory(App\Job::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
		]);

		$job->jsra()->create([]);
		$job->qc()->create([]);

		// Ensure job is created.
		$this->seeInDatabase('jobs', [
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
		]);

		// Create test installer.
		$api_token = 'testtoken123';
		$installer = factory(App\User::class, 'installer')->create([
			'api_token' => $api_token,
		]);

		// Associate installer with agent-admin.
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Associate installer with job, make primary installer.
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);
		$job->primary_installer_id = $installer->id;
		$job->save();

		// Test get QC form.
		$this->actingAs($installer)
			->json('GET', '/api/form/qc/' . $job->id, [
				'api_token' => $api_token,
			])
			->assertResponseStatus(200);

		$test_qc = [
			'good_condition'            => 'Y',
			'hoardings_aligned'         => 'Y',
			'good_paint'                => 'Y',
			'well_lubed'                => 'Y',
			'taped'                     => 'Y',
			'dust_supression_installed' => 'N',
			'anti_tamper_installed'     => 'N',
			'do_not_lean_installed'     => 'Y',
			'fingerprints_removed'      => 'Y',
			'floor_swept'               => 'Y',
			'waste_removed'             => 'Y',
			'hoarding_type'             => 'Kiosk',
			'installed_per_plan'        => 'Y',
			'set_out'                   => '1',
			'uprights_installed'        => 'Y',
			'stud_spec'                 => 'TITAN Eco',
			'double_stud'               => 'Y',
			'panel_installed'           => '12mm MDF',
			'screw_size'                => '8Gx40mm',
			'panel_fixing'              => 'Pine stud screw',
			'counterweights_quantity'   => '2',
			'counterweights_height'     => '2',
			'wind_compliant'            => 'Y',
			'returns'                   => 'Y',
			'bracing'                   => 'Y',
			'certificate'               => 'Y',
		];

		// Test post QC form.
		$this->actingAs($installer)
			->json('POST', '/api/form/qc/' . $job->id, $test_qc)
			->assertResponseStatus(200);

		// See db has successfully changed.
		$this->seeInDatabase('qc_forms', $test_qc);
	}

	/**
	 * Test post sign without user_id and password
	 * POST: /api/form/sign/{job_id}
	 */
	public function testPostSignWithoutUserID()
	{
		// Create a test job.
		$agent = factory(App\Agent::class)->create();

		$user = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);

		$client = factory(App\Client::class)->create([
			'agent_id' => $agent->id,
		]);

		$job = factory(App\Job::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
		]);

		$job->jsra()->create([]);
		$job->qc()->create([]);

		// Ensure job is created.
		$this->seeInDatabase('jobs', [
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
		]);

		// Create test installer.
		$api_token = 'testtoken123';
		$password  = str_random(8);
		$installer = factory(App\User::class, 'installer')->create([
			'api_token' => $api_token,
			'password'  => bcrypt($password),
		]);

		// Associate installer with agent-admin.
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Associate installer with job, make primary installer.
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);
		$job->primary_installer_id = $installer->id;
		$job->save();

		// Test sign himself
		$this->actingAs($installer)
			->post('/api/form/sign/' . $job->id, [
				'api_token' => $api_token,
			])
			->assertResponseStatus(200)
			->seeJsonStructure(['job_status']);

		// Check DB for sign : job_installers table
		$job_installers = App\JobInstaller::where('job_id', $job->id)
			->where('installer_id', $installer->id)
			->first();

		$this->assertNotNull($job_installers->form_signed_at);

		$job_updated = App\Job::find($job->id);
		$this->assertNotNull($job_updated->form_completed_at);
		$this->assertEquals('jsra-complete', $job_updated->status);
	}

	/**
	 * Test post sign another installer with user_id and password
	 * POST: /api/form/sign/{job_id}
	 */
	public function testPostSignOtherInstallerWithUserID()
	{
		// Create a test job.
		$agent  = factory(App\Agent::class)->create();
		$user   = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);
		$client = factory(App\Client::class)->create([
			'agent_id' => $agent->id,
		]);

		$job = factory(App\Job::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
		]);
		$job->jsra()->create([]);
		$job->qc()->create([]);

		// Create test primary installer.
		$api_token = 'testtoken123';
		$password  = str_random(8);
		$installer = factory(App\User::class, 'installer')->create([
			'api_token' => $api_token,
			'password'  => bcrypt($password),
		]);

		// Associate installer with agent-admin.
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Associate installer with job, make primary installer.
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);
		$job->primary_installer_id = $installer->id;
		$job->save();

		// Create other installer.
		$api_token_other = 'testtoken456';
		$password_other  = str_random(8);
		$installer_other = factory(App\User::class, 'installer')->create([
			'api_token' => $api_token_other,
			'password'  => bcrypt($password_other),
		]);

		// Associate installer with agent-admin.
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer_other->id,
		]);

		// Associate installer with job.
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer_other->id,
		]);

		// Testing with wrong password
		$this->actingAs($installer)
			->post('/api/form/sign/' . $job->id, [
				'api_token' => $api_token,
				'user_id'   => $installer->id,
				'password'  => str_random(6),
			])
			->assertResponseStatus(422);

		// Test sign himself with password, id
		$this->actingAs($installer)
			->post('/api/form/sign/' . $job->id, [
				'api_token' => $api_token,
				'user_id'   => $installer->id,
				'password'  => $password,
			])
			->assertResponseStatus(200)
			->seeJson([
				'signed_installers' => [
					$installer->id,
				],
				'job_status'        => 'pending',
			]);

		// Check DB for sign : job_installers table
		$job_installers = App\JobInstaller::where('job_id', $job->id)
			->where('installer_id', $installer->id)
			->first();

		$this->assertNotNull($job_installers->form_signed_at);

		// Check Job is not signed at the moment.
		$job_updated = App\Job::find($job->id);
		$this->assertNull($job_updated->form_completed_at);
		$this->assertEquals('pending', $job_updated->status);

		// Test sign other installer with password, id
		$this->actingAs($installer)
			->post('/api/form/sign/' . $job->id, [
				'api_token' => $api_token,
				'user_id'   => $installer_other->id,
				'password'  => $password_other,
			])
			->assertResponseStatus(200)
			->seeJson([
				'signed_installers' => [
					$installer->id,
					$installer_other->id,
				],
				'job_status'        => 'jsra-complete',
			]);

		// Check DB for sign : job_installers table
		$job_installer_other = App\JobInstaller::where('job_id', $job->id)
			->where('installer_id', $installer_other->id)
			->first();

		$this->assertNotNull($job_installer_other->form_signed_at);

		// Check Job is signed.
		$job_updated = App\Job::find($job->id);
		$this->assertNotNull($job_updated->form_completed_at);
		$this->assertEquals('jsra-complete', $job_updated->status);
	}

}