<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class JobsControllerTest extends TestCase
{
	// Jobs Controller test

	/**
	 * Test get index
	 * GET: /jobs
	 */
	public function testGetIndex()
	{
		// Create test user.
		$users = $this->createTestUser();
		$user = $users['agent-admin'];
		$agent = $users['agent'];

		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Test as agent-admin
		$this->actingAs($user)
			->visit('/jobs')
			->assertResponseStatus(200);

		// Test as installer
		$this->actingAs($installer)
			->visit('/jobs')
			->assertResponseStatus(200);
	}

	/**
	 * Test get view
	 * GET: /jobs/view/{id}
	 */
	public function testGetView()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$agent_admin = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $agent_admin, $client);

		// Test view with correct agent-admin
		$this->actingAs($agent_admin)
			->visit('/jobs/view/' . $job->id)
			->assertResponseStatus(200)
			->see($job->comments);

		// Test false case
		// Create another agent, agent-admin
		$users_2 = $this->createTestUser();
		$agent_2 = $users_2['agent'];
		$agent_admin_2 = $users_2['agent-admin'];

		// Test view with different agent-admin
		$this->actingAs($agent_admin_2)
			->get('/jobs/view/' . $job->id)
			->assertResponseStatus(404);
	}

	/**
	 * Test job creation, Get view => Post values from front-end
	 * Front-end create test
	 * GET: /jobs/create
	 */
	public function testGetCreate()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Validation test.
		$this->actingAs($user)
			->visit('/jobs/create')
			->type('invalid', 'num_doors')
			->type('invalid', 'dust_panel_height')
			->type('invalid', 'total_height')
			->type('invalid', 'total_length')
			->type('invalid', 'return_size')
			->type('56/13/11', 'start_date')
			->type('12pm', 'start_time')
			->press('Create Job')
			->see('The type field is required.')
			->see('The client field is required.')
			->see('The material field is required.')
			->see('The hoarding type field is required.')
			->see('The total length must be a number.')
			->see('The dust panel height must be a number.')
			->see('The total height must be a number.')
			->see('The return size must be a number.')
			->see('The start time does not match the format h:iA.')
			->see('The start date does not match the format d/m/Y.');

		$this->actingAs($user)
			->visit('/jobs/create')
			->press('Create Job')
			->type('other', 'material')
			->type('other', 'hoarding_type')
			->press('Create Job')
			->see('The material other field is required when material is other.')
			->see('The hoarding type other field is required when hoarding type is other.');

		// Test false case: creation with installer.
		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		$this->actingAs($installer)
			->get('/jobs/create')
			->assertResponseStatus(403);

		// Test success creating a job with agent-admin
		$this->actingAs($user)
			->visit('/jobs/create')
			->select('installation', 'type')
			->type($client->id, 'client')
			->type('123', 'internal_job_id')
			->type('1', 'shop_name')
			->type('123 Fake Street', 'address')
			->type('Fakeville', 'suburb')
			->select('QLD', 'state')
			->type('4000', 'postcode')
			->type('2', 'num_doors')
			->type('2', 'dust_panel_height')
			->type('2', 'total_height')
			->type('2', 'total_length')
			->type('2', 'return_size')
			->type('other', 'material')
			->type('other', 'hoarding_type')
			->type('Some Material', 'material_other')
			->type('Some Hoarding', 'hoarding_type_other')
			->type('16/02/2017', 'start_date')
			->type('06:25PM', 'start_time')
			->type('Some comment', 'comments')
			->press('Create Job');

		// Check DB change.
		$this->seeInDatabase('jobs', [
			'agent_id'            => $agent->id,
			'client_id'           => $client->id,
			'user_id'             => $user->id,
			'internal_job_id'     => '123',
			'type'                => 'installation',
			'status'              => 'pending',
			'shop_name'           => '1',
			'address'             => '123 Fake Street',
			'suburb'              => 'Fakeville',
			'state'               => 'QLD',
			'comments'            => 'Some comment',
			'postcode'            => '4000',
			'num_doors'           => '2',
			'dust_panel_height'   => '2000',
			'total_length'        => '2000',
			'total_height'        => '2000',
			'return_size'         => '2000',
			'hoarding_type_other' => 'Some Hoarding',
			'material_other'      => 'Some Material',
			'start_time'          => '2017-02-16 18:25:00',
		]);
	}

	/**
	 * Test job edit, Get view => Post values from front-end
	 * Front-end edit test
	 * GET: /jobs/edit/{id}
	 */
	public function testGetEdit()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Test false case: creation with installer.
		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		$this->actingAs($installer)
			->get('/jobs/edit/' . $job->id)
			->assertResponseStatus(403);

		$this->actingAs($installer)
			->post('/jobs/edit/' . $job->id, [
				'type'                => 'modification',
				'client'              => $client->id,
				'num_doors'           => '1',
				'dust_panel_height'   => '2',
				'total_height'        => '3',
				'total_length'        => '4',
				'return_size'         => '5',
				'material'            => 'other',
				'material_other'      => 'TestingMaterial',
				'hoarding_type'       => 'other',
				'hoarding_type_other' => 'TestingHoarding',
				'start_date'          => '16/04/2017',
				'start_time'          => '06:35PM',
			])
			->assertResponseStatus(403);

		// Test editing job.
		$this->actingAs($user)
			->visit('/jobs/edit/' . $job->id)
			->select('modification', 'type')
			->type($client->id, 'client')
			->type('1234', 'internal_job_id')
			->type('1', 'shop_name')
			->type('1234 New Street', 'address')
			->type('New Suburb', 'suburb')
			->select('NSW', 'state')
			->type('2000', 'postcode')
			->type('4', 'num_doors')
			->type('4', 'dust_panel_height')
			->type('4', 'total_height')
			->type('4', 'total_length')
			->type('4', 'return_size')
			->type('other', 'material')
			->type('other', 'hoarding_type')
			->type('New material', 'material_other')
			->type('New hoarding type', 'hoarding_type_other')
			->type('16/04/2017', 'start_date')
			->type('06:35PM', 'start_time')
			->type('This is the new comment', 'comments')
			->press('Update');

		// Check DB change.
		$this->seeInDatabase('jobs', [
			'agent_id'            => $agent->id,
			'client_id'           => $client->id,
			'user_id'             => $user->id,
			'internal_job_id'     => '1234',
			'type'                => 'modification',
			'status'              => 'pending',
			'shop_name'           => '1',
			'address'             => '1234 New Street',
			'suburb'              => 'New Suburb',
			'state'               => 'NSW',
			'comments'            => 'This is the new comment',
			'postcode'            => '2000',
			'num_doors'           => '4',
			'dust_panel_height'   => '4000',
			'total_length'        => '4000',
			'total_height'        => '4000',
			'return_size'         => '4000',
			'hoarding_type_other' => 'New hoarding type',
			'material_other'      => 'New material',
			'start_time'          => '2017-04-16 18:35:00',
		]);
	}

	/**
	 * Test get copy-job
	 * GET: /jobs/copy-job/{job_id}
	 */
	public function testGetCopyJob()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Test get
		$this->actingAs($user)
			->get('/jobs/copy-job/' . $job->id)
			->assertResponseStatus(200);
	}

	/**
	 * Test post copy job
	 * POST: /jobs/copy-job
	 */
	public function testPostCopyJobWithoutInstallersAndImages()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Do copy job
		$this->actingAs($user)
			->post('/jobs/copy-job', [
				'job_id'          => $job->id,
				'type'            => 'modification',
				'start_date'      => '01/05/2017',
				'start_time'      => '07:25PM',
				'internal_job_id' => '123',
				'copy_installers' => 'yes',
			]);

		// Check DB change: job
		$this->seeInDatabase('jobs', [
			'id'              => $job->id + 1,
			'type'            => 'modification',
			'client_id'       => $client->id,
			'internal_job_id' => '123',
			'start_time'      => '2017-05-01 19:25:00',
		]);
	}

	public function testPostCopyJobWithInstallersAndImages()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create and add test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Add installer by DB
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);

		// Create and add test image
		$image = factory(App\Image::class)->create([
			'user_id' => $installer->id,
			'job_id'  => $job->id,
		]);

		// Create a test product, add price, add to job.
		$product = factory(App\Product::class)->create();
		DB::table('product_prices')->insert([
			'product_id' => $product->id,
			'agent_id'   => $agent->id,
			'type'       => 'I',
		]);

		DB::table('job_products')->insert([
			'job_id'       => $job->id,
			'product_id'   => $product->id,
			'price'        => '1.00',
			'is_collected' => '0',
			'quantity'     => 1,
		]);

		// Create test contact, add to job by db
		$contact = factory(App\Contact::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
		]);

		DB::table('job_contacts')->insert([
			'job_id'     => $job->id,
			'contact_id' => $contact->id,
		]);

		// Do copy job
		$this->actingAs($user)
			->post('/jobs/copy-job', [
				'job_id'          => $job->id,
				'type'            => 'modification',
				'start_date'      => '01/05/2017',
				'start_time'      => '07:25PM',
				'internal_job_id' => '123',
				'copy_installers' => 'yes',
			]);

		// Check DB change: job
		$this->seeInDatabase('jobs', [
			'type'            => 'modification',
			'client_id'       => $client->id,
			'internal_job_id' => '123',
			'start_time'      => '2017-05-01 19:25:00',
		]);

		$job_copied = App\Job::where('type', 'modification')->first();

		// Check DB change: installers
		$this->seeInDatabase('job_installers', [
			'job_id'       => $job_copied->id,
			'installer_id' => $installer->id,
		]);

		// Check DB change: images
		$this->seeInDatabase('images', [
			'job_id' => $job_copied->id,
		]);

		// Check DB change: products
		$this->seeInDatabase('job_products', [
			'job_id'     => $job_copied->id,
			'product_id' => $product->id,
		]);

		// Check DB change: contacts
		$this->seeInDatabase('job_contacts', [
			'job_id'     => $job_copied->id,
			'contact_id' => $contact->id,
		]);
	}

	/**
	 * Test post delete job
	 * POST: /jobs/delete
	 */
	public function testPostDeleteJob()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Do delete job
		$this->actingAs($user)
			->post('/jobs/delete', [
				'job_id' => $job->id,
			]);

		// Check DB change
		$this->dontSeeInDatabase('jobs', [
			'id' => $job->id,
		]);
	}

	/**
	 * Test get add existing contacts
	 * GET: /jobs/add-existing-contacts
	 */
	public function testGetAddExistingContacts()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		$this->actingAs($user)
			->get('/jobs/add-existing-contacts/' . $job->id)
			->assertResponseStatus(200);
	}

	/**
	 * Test post add existing contacts
	 * POST: /jobs/add-existing-contacts
	 */
	public function testPostAddExistingContacts()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create test contact, added from start
		$contact_exist = factory(App\Contact::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
		]);

		DB::table('job_contacts')->insert([
			'job_id'     => $job->id,
			'contact_id' => $contact_exist->id,
		]);

		// Create new test contact to be added by API.
		$contact_new = factory(App\Contact::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
		]);

		// Do add contact to job
		$this->actingAs($user)
			->post('/jobs/add-existing-contacts/' . $job->id, [
				'contact_ids' => [
					$contact_exist->id,
					$contact_new->id,
				],
			]);

		// Check DB change
		$this->seeInDatabase('job_contacts', [
			'job_id'     => $job->id,
			'contact_id' => $contact_new->id,
		]);
	}

	/**
	 * Test get create contact
	 * GET: /jobs/create-contact
	 */
	public function testGetCreateContact()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		$this->actingAs($user)
			->get('/jobs/create-contact/' . $job->id)
			->assertResponseStatus(200);
	}

	/**
	 * Test post delete contacts
	 * POST: /jobs/delete-contact/{contact_id}
	 */
	public function testPostDeleteContacts()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create test contact, add to job by db
		$contact = factory(App\Contact::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
		]);

		DB::table('job_contacts')->insert([
			'job_id'     => $job->id,
			'contact_id' => $contact->id,
		]);

		// Do delete contact
		$this->actingAs($user)
			->post('/jobs/delete-contact/' . $contact->id, [
				'job_id' => $job->id,
			]);

		// Check DB change
		$this->dontSeeInDatabase('job_contacts', [
			'job_id'     => $job->id,
			'contact_id' => $contact->id,
		]);
	}

	/**
	 * Test post add note
	 * POST: /jobs/add-note
	 */
	public function testPostAddNote()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		$test_note = 'Test note';

		// Do add note
		$this->actingAs($user)
			->post('/jobs/add-note', [
				'job_id'  => $job->id,
				'message' => $test_note,
			]);

		// Check DB change
		$this->seeInDatabase('job_notes', [
			'job_id'  => $job->id,
			'message' => $test_note,
		]);
	}

	/**
	 * Test post add installers
	 * POST: /jobs/add-installers
	 */
	public function testPostAddInstallers()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Do add installer
		$this->actingAs($user)
			->post('/jobs/add-installers', [
				'job_id'        => $job->id,
				'installer_ids' => [$installer->id],
			]);

		// Check DB change
		$this->seeInDatabase('job_installers', [
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);

		// Check DB change for primary installer
		$this->SeeInDatabase('jobs', [
			'id'                   => $job->id,
			'primary_installer_id' => $installer->id,
		]);

		// Do add installer again.
		$this->actingAs($user)
			->post('/jobs/add-installers', [
				'job_id'        => $job->id,
				'installer_ids' => [$installer->id],
			]);
	}

	/**
	 * Test post delete installer
	 * POST: /jobs/delete-installer/{installer_id}
	 */
	public function testPostDeleteInstaller()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Add installer by DB
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);

		// Do delete installer
		$this->actingAs($user)
			->post('/jobs/delete-installer/' . $installer->id, [
				'job_id' => $job->id,
			]);

		// Check DB change
		$this->dontSeeInDatabase('job_installers', [
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);

		// Add installer again as primary installer.
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);
		$job->primary_installer_id = $installer->id;
		$job->save();

		// Do delete primary installer
		$this->actingAs($user)
			->post('/jobs/delete-installer/' . $installer->id, [
				'job_id' => $job->id,
			]);

		// Check DB change
		$this->dontSeeInDatabase('job_installers', [
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);
	}

	/**
	 * Test post add document, delete document.
	 * POST: /jobs/add-document
	 * POST: /jobs/delete-document/{document_id}
	 */
	public function testPostAddDeleteDocument()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Do add document
		$stub_pdf = __DIR__ . '/test_stubs/test.pdf';
		$name_pdf = str_random(8) . '.pdf';
		$path_pdf = sys_get_temp_dir() . '/' . $name_pdf;
		copy($stub_pdf, $path_pdf);
		$file_pdf = new UploadedFile($path_pdf, $name_pdf, filesize($path_pdf), 'application/x-pdf', null, true);

		$this->actingAs($user)
			->call('POST', '/jobs/add-document', [
				'job_id' => $job->id,
			], [], [
				'file' => $file_pdf,
			]);

		// Check DB.
		$this->seeInDatabase('job_documents', [
			'job_id' => $job->id,
		]);

		// Check file has copied correctly.
		$this->assertFileExists('storage/job_documents/' . $job->documents()->first()->name);

		// Do delete document.
		$document = $job->documents()->first();
		$this->actingAs($user)
			->call('POST', '/jobs/delete-document/' . $document->id, [
				'document_id' => $document->id,
			]);

		// Check DB.
		$this->dontSeeInDatabase('job_documents', [
			'job_id' => $job->id,
		]);
	}

	/**
	 * Test post add product.
	 * POST: /jobs/add-product
	 */
	public function testPostAddProduct()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create a test product, add price.
		$product = factory(App\Product::class)->create();
		DB::table('product_prices')->insert([
			'product_id' => $product->id,
			'agent_id'   => $agent->id,
			'type'       => 'I',
		]);

		// Do add product.
		$this->actingAs($user)
			->post('/jobs/add-product', [
				'product_id' => $product->id,
				'quantity'   => 1,
				'job_id'     => $job->id,
			]);

		// Do add product again to check error
		$this->actingAs($user)
			->post('/jobs/add-product', [
				'product_id' => $product->id,
				'quantity'   => 1,
				'job_id'     => $job->id,
			])
			->assertResponseStatus(422);

		// Check DB.
		$this->seeInDatabase('job_products', [
			'job_id'     => $job->id,
			'product_id' => $product->id,
			'quantity'   => 1,
		]);
	}

	/**
	 * Test post edit product.
	 * POST: /jobs/edit-product/{job_id}/{product_id}
	 */
	public function testPostEditProduct()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create a test product, add price, add to job.
		$product = factory(App\Product::class)->create();
		DB::table('product_prices')->insert([
			'product_id' => $product->id,
			'agent_id'   => $agent->id,
			'type'       => 'I',
		]);
		DB::table('job_products')->insert([
			'job_id'       => $job->id,
			'product_id'   => $product->id,
			'price'        => '1.00',
			'is_collected' => '0',
			'quantity'     => 1,
		]);

		// Do edit.
		$this->actingAs($user)
			->post('/jobs/edit-product/' . $job->id . '/' . $product->id, [
				'quantity' => 10,
				'job_id'   => $job->id,
			]);

		// Check DB.
		$this->seeInDatabase('job_products', [
			'quantity'   => 10,
			'job_id'     => $job->id,
			'product_id' => $product->id,
		]);
	}

	/**
	 * Test post delete product.
	 * POST: /jobs/delete-product/{job_product_id}
	 */
	public function testPostDeleteProduct()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create a test product, add price, add to job.
		$product = factory(App\Product::class)->create();
		DB::table('product_prices')->insert([
			'product_id' => $product->id,
			'agent_id'   => $agent->id,
			'type'       => 'I',
		]);
		DB::table('job_products')->insert([
			'job_id'       => $job->id,
			'product_id'   => $product->id,
			'price'        => '1.00',
			'is_collected' => '0',
			'quantity'     => 1,
		]);

		// Do delete.
		$this->actingAs($user)
			->post('/jobs/delete-product/' . $job->products()->first()->id, []);

		// Check DB.
		$this->dontSeeInDatabase('job_products', [
			'job_id'     => $job->id,
			'product_id' => $product->id,
		]);
	}

	/**
	 * Test get search installers
	 * GET: /jobs/search-installers
	 */
	public function testGetSearchInstallers()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Add installer by DB
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);

		// Do search
		$this->actingAs($user)
			->get('/jobs/search-installers?global_search=' . $installer->first_name)
			->assertResponseStatus(200)
			->see($installer->second_name);
	}

	/**
	 * Test post set primary installer
	 * POST: /jobs/set-primary-installer/{job_id}
	 */
	public function testPostSetPrimaryInstaller()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Create test installer.
		$installer = factory(App\User::class, 'installer')->create();
		DB::table('agent_installers')->insert([
			'agent_id'     => $agent->id,
			'installer_id' => $installer->id,
		]);

		// Add installer by DB
		DB::table('job_installers')->insert([
			'job_id'       => $job->id,
			'installer_id' => $installer->id,
		]);

		// Do set primary installer
		$this->actingAs($user)
			->post('/jobs/set-primary-installer/' . $job->id, [
				'installer_id' => $installer->id,
			]);

		// Check DB change
		$this->SeeInDatabase('jobs', [
			'id'                   => $job->id,
			'primary_installer_id' => $installer->id,
		]);
	}

	/**
	 * Test get sqc
	 * POST: /jobs/sqc/{job_id}
	 */
	public function testGetSqc()
	{
		// Create test user.
		$users = $this->createTestUser();
		$agent = $users['agent'];
		$user = $users['agent-admin'];
		$client = $users['client'];

		// Create a test job.
		$job = $this->createTestJob($agent, $user, $client);

		// Do get sqc
		$this->actingAs($user)
			->get('/jobs/sqc/' . $job->id)
			->assertResponseStatus(200);

		// Test false cases.
		// Create test user
		$users_2 = $this->createTestUser();
		$user_2 = $users_2['agent-admin'];

		$this->actingAs($user_2)
			->get('/jobs/sqc/' . $job->id)
			->assertResponseStatus(404);
	}

	// Helper functions for tests

	/**
	 * Creates a testing agent, agent-admin, client
	 */

	public function createTestUser()
	{
		$agent = factory(App\Agent::class)->create();
		$agent_admin = factory(App\User::class, 'agent-admin')->create([
			'agent_id' => $agent->id,
		]);
		$client = factory(App\Client::class)->create([
			'agent_id' => $agent->id,
		]);

		return [
			'agent'       => $agent,
			'agent-admin' => $agent_admin,
			'client'      => $client,
		];
	}

	/**
	 * creates a testing job
	 * @param $agent  App\Agent agent from createTestUsers
	 * @param $user   App\User agent-admin from createTestUsers
	 * @param $client App\Client client from createTestUsers
	 * @return mixed job created.
	 */
	public function createTestJob($agent, $user, $client)
	{
		$job = factory(App\Job::class)->create([
			'agent_id'  => $agent->id,
			'client_id' => $client->id,
			'user_id'   => $user->id,
			'type'      => 'installation',
		]);

		$job->jsra()->create([]);
		$job->qc()->create([]);

		return $job;
	}

}
