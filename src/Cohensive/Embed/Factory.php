<?php
namespace Cohensive\Embed;

use Illuminate\Foundation\Application;

class Factory
{
    /**
     * Available embed providers.
     *
     * @var array
     */
    protected $providers;

    /**
     * SSL flag forcing SSL-only URIs.
     *
     * @var array
     */
    protected $ssl;

    /**
     * Create Embed factory and set providers from config.
     *
     * @param  array  $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->providers = $config['providers'];
        $this->ssl = $config['ssl'];
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
        $embed = new Embed($url, $options);
        $embed->setProviders($this->providers);
        $embed->setSSL($this->ssl);

        return $embed;
    }
}
