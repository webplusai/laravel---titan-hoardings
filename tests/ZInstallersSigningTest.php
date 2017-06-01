<?php

use Illuminate\Support\Facades\DB;

class ZInstallersSigningTest extends TestCase
{
	// Testing Installers' signing jsra process.

	/**
	 * Test post sign another installer with user_id and password, then check installers list by installers api endpoint.
	 * Edited from APIFormControllerTest / testPostSignOtherInstallerWithUserID()
	 * POST: /api/form/sign/{job_id}
	 * GET: /api/installers/list-by-job/{job_id}
	 */
	public function testSignOtherInstallerAndGetInstallersShouldReturnHasSigned()
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

		// Check installers list, both have not signed.
		$this->actingAs($installer)
			->get('/api/installers/list-by-job/' . $job->id . '?api_token=' . $api_token)
			->seeJsonSubset([
				['id' => $installer->id, 'has_signed' => false],
				['id' => $installer_other->id, 'has_signed' => false],
			]);

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

		// Check installers list, One signed, other not signed.
		$this->actingAs($installer)
			->get('/api/installers/list-by-job/' . $job->id . '?api_token=' . $api_token)
			->seeJsonSubset([
				['id' => $installer->id, 'has_signed' => true],
				['id' => $installer_other->id, 'has_signed' => false],
			]);

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

		// Check installers list, both have signed.
		$this->actingAs($installer)
			->get('/api/installers/list-by-job/' . $job->id . '?api_token=' . $api_token)
			->seeJsonSubset([
				['id' => $installer->id, 'has_signed' => true],
				['id' => $installer_other->id, 'has_signed' => true],
			]);
	}

}