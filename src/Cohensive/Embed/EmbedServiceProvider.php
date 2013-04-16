<?php
namespace Cohensive\Embed;

use Illuminate\Support\ServiceProvider;

class EmbedServiceProvider extends ServiceProvider
{
  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = true;

	public function boot()
	{
    $this->package('cohensive/embed');
    // Register the package configuration with the loader.
    $this->app['config']->package('cohensive/embed', __DIR__.'/../config');
	}

	/**
	 * Register the service provider.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register()
	{
		$this->app['embed'] = $this->app->share(function($app) {
			return new Factory($app);
		});
	}

	public function provides()
	{
		return array('embed');
	}

}
