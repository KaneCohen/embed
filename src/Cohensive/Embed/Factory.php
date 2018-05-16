<?php
namespace Cohensive\Embed;

use Illuminate\Foundation\Application;

class Factory
{
    /**
     * Configs.
     *
     * @var array
     */
    protected $config;

    /**
     * Available embed providers.
     *
     * @var array
     */
    protected $providers;

    /**
     * Create Embed factory and set providers from config.
     *
     * @param  array  $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->providers = $config['providers'];
    }

    /**
     * Create a new Embed instance.
     *
     * @param  array  $url
     * @param  array  $options  Extra options like iframe attributes or params.
     * @return Cohenisve\Embed\Embed
     */
    public function make($url = null, $options = null)
    {
        $embed = new Embed($url, $options, $this->config);
        $embed->setProviders($this->providers);

        return $embed;
    }
}
