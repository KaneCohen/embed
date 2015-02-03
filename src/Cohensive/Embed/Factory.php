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
     * Create Embed factory and set providers from config.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * Create a new Embed instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return Cohenisve\Embed\Embed
     */
    public function make($url = null, $config = null)
    {
        $embed = new Embed($url, $config);
        $embed->setProviders($this->providers);
        return $embed;
    }
}
