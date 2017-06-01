<?php

use Illuminate\Http\UploadedFile;

class ResourcesControllerTest extends TestCase
{
	// Resources controller test

	/**
	 * Test getIndex
	 * GET: /resources
	 * GET: /resources/create
	 * GET: /resources/edit/{resource_id}
	 * GET: /resources/delete/{resource_id}
	 */
	public function testGetRoutes()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add some resource
		$resource_list = factory(App\Resource::class, 3)->create();

		$this->actingAs($user)
			->visit('/resources')
			->assertResponseStatus(200)
			->see('Resources');

		// Check if all resources are in db
		foreach ($resource_list as $resource) {
			$this->see($resource->name);
		}

		$this->actingAs($user)
			->visit('/resources/create')
			->assertResponseStatus(200)
			->visit('/resources/edit/' . $resource_list[0]->id)
			->assertResponseStatus(200)
			->visit('/resources/delete/' . $resource_list[0]->id)
			->assertResponseStatus(200);
	}

	/**
	 * Test postCreate with Video link
	 * POST: /resources/create
	 */
	public function testPostCreateWithUrl()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add fake products to database.
		factory(App\Product::class, 5)->create();

		// Valid creation with Video link
		$this->actingAs($user);

		$this->call('POST', '/resources/create', [
			'name'        => 'TestResource',
			'description' => 'TestDescription',
			'type'        => 'video',
			'video'       => 'http://testVideo.com',
			'product_ids' => ['2'],
		]);

		$this->assertResponseStatus(200);

		$this->seeInDatabase('resources', [
			'name' => 'TestResource',
			'type' => 'video',
			'url'  => 'http://testVideo.com',
		]);

		$this->seeInDatabase('product_resources', [
			'resource_id' => '1',
			'product_id'  => '2',
		]);
	}

	/**
	 * Test postCreate with Video PDF file
	 * POST: /resources/create
	 */
	public function testPostCreateWithPdf()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add fake products to database.
		factory(App\Product::class, 5)->create();

		// Valid creation with PDF file
		$stub = __DIR__ . '/test_stubs/test.pdf';
		$file = new UploadedFile($stub, 'test.pdf', filesize($stub), 'application/x-pdf', null, true);

		$this->actingAs($user);

		$this->call('POST', '/resources/create', [
			'name'        => 'TestResource2',
			'description' => 'TestDescription2',
			'type'        => 'file',
			'product_ids' => ['2'],
		], [], [
			'file' => $file,
		]);

		$this->assertResponseStatus(200);

		$this->seeInDatabase('resources', [
			'name' => 'TestResource2',
			'type' => 'file',
			'url'  => 'storage/resources/1.pdf',
		]);
	}

	/**
	 * Test postCreate with image file
	 * POST: /resources/create
	 */
	public function testPostCreateWithImage()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add fake products to database.
		factory(App\Product::class, 5)->create();

		// Valid creation with image file
		$stub = __DIR__ . '/test_stubs/test.jpg';

		$file = new UploadedFile($stub, 'test.jpg', filesize($stub), 'image/jpeg', null, true);

		$this->actingAs($user)
			->call('POST', '/resources/create', [
				'name'        => 'TestResource3',
				'description' => 'TestDescription3',
				'type'        => 'image',
				'product_ids' => ['3'],
			], [], [
				'image' => $file,
			]);

		$this->assertResponseStatus(200);

		$this->seeInDatabase('resources', [
			'name' => 'TestResource3',
			'type' => 'image',
			'url'  => 'storage/resources/1.jpeg',
		]);
	}

	/**
	 * Test postEdit with Video Link
	 * POST: /resources/edit
	 */
	public function testPostEditWithVideo()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add fake products to database.
		factory(App\Product::class, 5)->create();

		// Create test resource
		factory(App\Resource::class, 1)->create();

		$this->actingAs($user);

		$this->call('POST', '/resources/edit/1', [
			'name'        => 'NewResource',
			'description' => 'NewDescription',
			'type'        => 'video',
			'video'       => 'http://newVideo.com',
			'product_ids' => ['3'],
		]);

		$this->assertResponseStatus(200);

		$this->seeInDatabase('resources', [
			'name' => 'NewResource',
			'type' => 'video',
			'url'  => 'http://newVideo.com',
		]);

		$this->seeInDatabase('product_resources', [
			'resource_id' => '1',
			'product_id'  => '3',
		]);
	}

	/**
	 * Test postEdit with PDF
	 * POST: /resources/edit
	 */
	public function testPostEditWithPdf()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add fake products to database.
		factory(App\Product::class, 5)->create();

		// Create test resource
		factory(App\Resource::class, 1)->create([
			'type' => 'file',
			'url'  => '',
		]);

		// Valid edit with PDF file
		$stub = __DIR__ . '/test_stubs/test.pdf';

		$file = new UploadedFile($stub, 'test.pdf', filesize($stub), 'application/x-pdf', null, true);

		$this->actingAs($user);

		$this->call('POST', '/resources/edit/1', [
			'name'        => 'NewResource2',
			'description' => 'NewDescription2',
			'type'        => 'file',
			'product_ids' => ['2'],
		], [], [
			'file' => $file,
		]);

		$this->assertResponseStatus(200);

		$this->seeInDatabase('resources', [
			'name' => 'NewResource2',
			'type' => 'file',
			'url'  => 'storage/resources/1.pdf',
		]);
	}

	/**
	 * Test postEdit with Image
	 * POST: /resources/edit
	 */
	public function testPostEditWithImage()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add fake products to database.
		factory(App\Product::class, 5)->create();

		// Create test resource
		factory(App\Resource::class, 1)->create([
			'type' => 'image',
			'url'  => 'storage/resources/test.jpeg',
		]);

		$stub = __DIR__ . '/test_stubs/test.jpg';

		$file = new UploadedFile($stub, 'test.jpg', filesize($stub), 'image/jpeg', null, true);

		$this->actingAs($user);

		$this->call('POST', '/resources/edit/1', [
			'name'        => 'NewResource3',
			'description' => 'NewDescription3',
			'type'        => 'image',
			'product_ids' => ['3'],
		], [], [
			'image' => $file,
		]);

		$this->assertResponseStatus(200);

		$this->seeInDatabase('resources', [
			'name' => 'NewResource3',
			'type' => 'image',
			'url'  => 'storage/resources/1.jpeg',
		]);
	}

	/**
	 * Test postDelete
	 * POST: /resources/delete
	 */
	public function testDeleteResource()
	{
		// Create test user
		$user = factory(App\User::class, 'global-admin')->create();

		// Add some testing resource
		factory(App\Resource::class, 5)->create();

		// Invalid delete
		$fake_id = 999;
		$this->actingAs($user)
			->post('/resources/delete/' . $fake_id, [])
			->assertResponseStatus(404);

		// Valid delete
		$real_id = 1;
		$this->seeInDatabase('resources', ['id' => $real_id]);
		$this->actingAs($user)
			->post('/resources/delete/' . $real_id)
			->assertResponseStatus(200);

		// Test it has removed from db
		$this->dontSeeInDatabase('resources', ['id' => $real_id]);
	}

}
