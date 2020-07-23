<?php
namespace App\Modules\Att\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class AttServiceProvider extends ServiceProvider
{
	/**
	 * Register the Att module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Att\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Att module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('att', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('att', base_path('resources/views/vendor/att'));
		View::addNamespace('att', realpath(__DIR__.'/../Resources/Views'));
	}
}
