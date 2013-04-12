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

	/**
	 * Register the service provider.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register()
	{
		$this->app['embed'] = $this->app->share(function($app) {
			return new Factory();
		});
	}

	public function provides()
	{
		return array('embed');
	}

}
