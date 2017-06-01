<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
	/**
	 * The base URL to use while testing the application.
	 *
	 * @var string
	 */
	protected $baseUrl = 'http://localhost';

	protected static $migrated = false;

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__ . '/../bootstrap/app.php';

		$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

		return $app;
	}

	public function setUp()
	{
		parent::setUp();

		// If this is the first test of the class, drop all tables in the
		// database and re-migrate.
		if (!self::$migrated) {
			self::dropAllTables();
			Artisan::call('migrate');
			self::$migrated = true;
		}

		self::resetDatabase();

		// Insert HoardingTypes and Materials with seeder
		$this->seed(HoardingTypesSeeder::class);
		$this->seed(MaterialsSeeder::class);
	}

	public static function resetDatabase()
	{
		$tables = DB::table('information_schema.TABLES')
			->where('TABLE_SCHEMA', env('DB_DATABASE'))
			->where('AUTO_INCREMENT', '!=', 1)
			->lists('TABLE_NAME');

		DB::statement('SET foreign_key_checks = 0');

		foreach ($tables as $table) {
			DB::statement("TRUNCATE TABLE `$table`");
		}

		DB::statement('SET foreign_key_checks = 1');
	}

	public static function dropAllTables()
	{
		$tables = DB::table('information_schema.TABLES')
			->where('TABLE_SCHEMA', env('DB_DATABASE'))
			->lists('TABLE_NAME');

		DB::statement('SET foreign_key_checks = 0');

		foreach ($tables as $table) {
			DB::statement("DROP TABLE `$table`");
		}

		DB::statement('SET foreign_key_checks = 1');
	}

}
