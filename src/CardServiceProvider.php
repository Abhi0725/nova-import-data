<?php

namespace Squareboat\ImportData;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class CardServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->app->booted(function () {
				$this->routes();
			});

		$this->publishes([
				__DIR__ .'/config.php' => config_path('squareboat-import-data.php'),
			]);

		Nova::serving(function (ServingNova $event) {
				Nova::script('ImportData', __DIR__ .'/../dist/js/card.js');
				Nova::style('ImportData', __DIR__ .'/../dist/css/card.css');
			});
	}

	/**
	 * Register the card's routes.
	 *
	 * @return void
	 */
	protected function routes() {
		if ($this->app->routesAreCached()) {
			return;
		}

		Route::middleware(['nova'])
			->prefix('nova-vendor/ImportData')
			->group(__DIR__ .'/../routes/api.php');
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
