<?php
namespace Embed;

class Factory {

	public function make($url = null, $config = null)
	{
		return new Embed($url, $config);
	}

}
