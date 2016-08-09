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
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->package('cohensive/embed');
        $this->app->singleton('embed', function($app) {
            return new Factory($app);
        });
    }

    public function provides()
    {
        return array('embed');
    }
}
