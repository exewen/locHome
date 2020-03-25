<?php
namespace App\Modules\Sso\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class SsoServiceProvider extends ServiceProvider
{
	/**
	 * Register the Sso module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.
		App::register('App\Modules\Sso\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	/**
	 * Register the Sso module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		Lang::addNamespace('sso', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('sso', base_path('resources/views/vendor/sso'));
		View::addNamespace('sso', realpath(__DIR__.'/../Resources/Views'));
	}
}
