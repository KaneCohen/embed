<?php namespace Cohensive\Embed;

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
     * Boots the service provider.
     */
    public function boot()
    {
        $source = __DIR__ . '/../../config/config.php';
        $this->mergeConfigFrom($source, 'embed');
        $this->publishes([
            $source => config_path('embed.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('embed', function($app) {
            return new Factory($app->config->get('embed.providers'));
        });
    }

    public function provides()
    {
        return ['embed'];
    }
}
