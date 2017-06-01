<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * Factory for basic User
 */
$factory->define(App\User::class, function (Faker\Generator $faker) {
	return [
		'first_name'     => $faker->firstName,
		'last_name'      => $faker->lastName,
		'email'          => $faker->safeEmail,
		'password'       => bcrypt(str_random(10)),
		'remember_token' => str_random(10),
		'timezone'       => 'Australia/Brisbane',
	];
});

/**
 * Factory for Agent
 */
$factory->define(App\Agent::class, function (Faker\Generator $faker) {
	return [
		'name'              => $faker->name,
		'abn'               => '123456789',
		'billing_address'   => '123 Fake Street',
		'billing_suburb'    => 'Fakeville',
		'billing_state'     => 'QLD',
		'billing_postcode'  => '4000',
		'shipping_address'  => '123 Fake Street',
		'shipping_suburb'   => 'Fakeville',
		'shipping_state'    => 'QLD',
		'shipping_postcode' => '4000',
		'phone'             => '0400000000',
		'mobile'            => '0400000000',
		'email'             => 'agent@example.com',
		'billing_email'     => 'agent@example.com',
		'num_employees'     => '0',
		'bank_acc_name'     => 'Example Bank Account',
		'bank_acc_bsb'      => '123456789',
		'bank_acc_no'       => '123456789',
	];
});

/**
 * Factory for User / global-admin
 * No agent_id.
 */
$factory->defineAs(App\User::class, 'global-admin', function (Faker\Generator $faker) use ($factory) {
	$user = $factory->raw(App\User::class);

	return array_merge($user, [
		'type'     => 'global-admin',
		'agent_id' => null,
	]);
});

/**
 * Factory for User / agent-admin
 */
$factory->defineAs(App\User::class, 'agent-admin', function (Faker\Generator $faker) use ($factory) {
	$user = $factory->raw(App\User::class);

	return array_merge($user, [
		'type'     => 'agent-admin',
		'agent_id' => factory(App\Agent::class)->create()->id,
	]);
});

/**
 * Factory for User / agent-user
 */
$factory->defineAs(App\User::class, 'agent-user', function (Faker\Generator $faker) use ($factory) {
	$user = $factory->raw(App\User::class);

	return array_merge($user, [
		'type'     => 'agent-user',
		'agent_id' => factory(App\Agent::class)->create()->id,
	]);
});

/**
 * Factory for installer
 * agent_id will be null,
 * installers are connected via pivot table : agent_installers
 */
$factory->defineAs(App\User::class, 'installer', function (Faker\Generator $faker) use ($factory) {
	$user = $factory->raw(App\User::class);

	return array_merge($user, [
		'type'     => 'installer',
		'agent_id' => null,
	]);
});

/**
 * Factory for Client
 */
$factory->define(App\Client::class, function (Faker\Generator $faker) {
	return [
		'agent_id'      => factory(App\Agent::class)->create(),
		'name'          => $faker->name,
		'email'         => $faker->email,
		'billing_email' => $faker->email,
		'mobile'        => '0400000000',
		'fax'           => '123456789',
		'abn'           => '123456789',
	];
});

/**
 * Factory for Job
 */
$factory->define(App\Job::class, function (Faker\Generator $faker) {
	$agent = factory(App\Agent::class)->create();
	$agent_admin = factory(App\User::class, 'agent-admin')->create([
		'agent_id' => $agent->id,
	]);
	$client = factory(App\Client::class)->create([
		'agent_id' => $agent->id,
	]);

	return [
		'agent_id'            => $agent->id,
		'client_id'           => $client->id,
		'user_id'             => $agent_admin->id,
		'hoarding_type_id'    => null,
		'material_id'         => null,
		'internal_job_id'     => rand(0, 100),
		'type'                => 'installation',
		'status'              => 'pending',
		'shop_name'           => str_random(50),
		'address'             => $faker->address,
		'suburb'              => $faker->city,
		'state'               => 'QLD',
		'comments'            => str_random(50),
		'postcode'            => rand(1000, 9999),
		'num_doors'           => rand(0, 10),
		'dust_panel_height'   => rand(0, 10),
		'total_length'        => rand(0, 10),
		'total_height'        => rand(0, 10),
		'return_size'         => rand(0, 10),
		'hoarding_type_other' => str_random(10),
		'material_other'      => str_random(10),
		'start_time'          => '2015-10-28 19:18:44',
		'form_completed_at'   => '2015-10-28 19:18:44',
		'created_at'          => '2015-10-28 19:18:44',
		'updated_at'          => '2015-10-28 19:18:44',
	];
});

/**
 * Factory for Product
 */
$factory->define(App\Product::class, function (Faker\Generator $faker) {
	return [
		'name'                      => $faker->word,
		'default_price'             => rand(1, 10),
		'height_of_panel'           => rand(1, 10),
		'height_of_dust_supression' => rand(1, 10),
		'width'                     => rand(1, 10),
		'depth'                     => rand(1, 10),
		'weight'                    => rand(1, 10),
		'wind_rating'               => rand(1, 10),
	];
});

/**
 * Factory for Resource
 */
$factory->define(App\Resource::class, function (Faker\Generator $faker) {
	return [
		'name'        => $faker->word,
		'description' => $faker->sentence(),
		'type'        => 'video',
		'url'         => $faker->url,
	];
});

/**
 * Factory for Contact
 */
$factory->define(App\Contact::class, function (Faker\Generator $faker) {
	$agent = factory(App\Agent::class)->create();
	$client = factory(App\Client::class)->create([
		'agent_id' => $agent->id,
	]);

	return [
		'agent_id'   => $agent->id,
		'client_id'  => $client->id,
		'first_name' => $faker->firstName,
		'last_name'  => $faker->lastName,
		'email'      => $faker->email,
		'phone'      => $faker->phoneNumber,
		'position'   => 'Fake Position',
		'type'       => 'Other',
	];
});

/**
 * Factory for Image
 */
$factory->define(App\Image::class, function (Faker\Generator $faker) {
	$installer = factory(App\User::class, 'installer')->create();
	$job = factory(App\Job::class)->create();

	$filename = $job->id . '-' . substr(md5(microtime()), 0, 10);

	return [
		'user_id'    => $installer->id,
		'job_id'     => $job->id,
		'filename'   => $filename,
		'extension'  => 'jpg',
		'caption'    => 'TestCaption',
		'type'       => 'pre-installation',
		'created_at' => '2015-10-28 19:18:44',
		'updated_at' => '2015-10-28 19:18:44',
	];
});

/**
 * Factory for invitation of installer
 */
$factory->define(App\Invitation::class, function (Faker\Generator $faker) {
	$user = factory(App\User::class, 'installer')->create();
	return [
		'token'   => substr(md5(microtime()), 0, 10),
		'user_id' => $user->id,
	];
});