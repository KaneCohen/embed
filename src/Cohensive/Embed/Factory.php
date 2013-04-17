<?php
namespace Cohensive\Embed;

class Factory {

	protected $providers;

	public function __construct($app)
	{
		$config = $app['config'];
		$this->providers = $config->get('embed::providers');
	}

	public function make($url = null, $config = null)
	{
		$embed = new Embed($url, $config);
		$embed->setProviders($this->providers);
		return $embed;
	}

}
