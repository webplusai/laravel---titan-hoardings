<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Agent;
use Auth;
use Blade;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		view()->composer('layouts.default', function ($view) {
			if (Auth::user()->isGlobalAdmin()) {
				$agents = Agent::orderBy('name')->get();
				$view->with('_agents', $agents);
			}
		});

		Blade::directive('printvalue', function($value) {
			return "<?php if ($value == 'Y') echo '<i class=\"fa fa-check text-success\"></i>'; elseif ($value == 'N') echo '<i class=\"fa fa-times text-danger\"></i>'; elseif ($value == 'NA') echo '<span class=\"text-success\">N/A</span>'; ?>";
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
		$this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
	}

}
