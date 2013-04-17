<?php
namespace Cohensive\Embed;

class Embed
{

	// could be just a string containing url
	protected $url;

	protected $attributes;

	protected $params;

	// providers array
	protected $providers;

	// provider array after parse run
	protected $provider;

	// array of matches after parse run
	protected $matches;

	/*
	 * url - duh
	 * config - multidimensional array
	 * might contain params or attributes for iframe/object
	 */
	public function __construct($url = null, $config = null)
	{
		if ( ! is_null($url) ) {
			$this->url = $url;
		}

		if ( ! is_null($config) ) {
			$this->attributes = (isset($config['attributes']) ? $config['attributes'] : null);
			$this->params = (isset($config['params']) ? $config['params'] : null);
		}
	}

	public function setUrl(string $url)
	{
		$this->url = $url;
	}

	// key - mixed string/array
	public function setParam($key, $val = null)
	{
		if (is_array($key) ) {
			foreach ($key as $k => $val) {
				$this->params[$k] = $val;
			}
		} else {
			$this->params[$key] = $val;
		}

		$this->updateProvider();

	}

	// key - mixed string/array
	public function setAttr($key, $val = null)
	{
		if (is_array($key) ) {
			foreach ($key as $k => $val) {
				$this->attributes[$k] = $val;
			}
		} else {
			$this->attributes[$key] = $val;
		}

		// if provider already set, update it's data
		$this->updateProvider();
	}

	// method to parse url. Can be used multiple times after resetting url
	public function parseUrl()
	{
		if ( ! is_null($this->url) ) {

			foreach ($this->providers as $provider) {
				if ( is_array($provider['url']) ) {
					// multiple urls
					foreach ($provider['url'] as $pattern) {
						if ( preg_match('~'.$pattern.'~imu', $this->url, $matches) ) {
							$this->matches = $matches;
							$this->provider = $provider;
							$this->parseProvider($this->provider['info'], $matches);
							$this->parseProvider($this->provider['render'], $matches);
							$this->updateProvider();
							return $this;
						}
					}

				} else {
					if ( preg_match('~'.$provider['url'].'~imu', $this->url, $matches) ) {
						$this->matches = $matches;
						$this->provider = $provider;
						$this->parseProvider($this->provider['info'], $matches);
						$this->parseProvider($this->provider['render'], $matches);
						$this->updateProvider();
						return $this;
					}
				}
			}

		} else {
			return false;
		}
	}

	public function parseData()
	{
		if (isset($this->provider['dataCallback'])) {
			$this->provider['data'] = $this->provider['dataCallback']($this);
		}
		return $this;
	}


	public function parseProvider(&$array, $matches)
	{

		// check if we have an iframe creation array
		foreach ($array as $key => $val) {
			if ( is_array($val) ) {
				$array[$key] = $this->parseProvider($val, $matches);
			} else {
				for ($i=1; $i<count($matches); $i++) {
					$array[$key] = str_replace('{'.$i.'}', $matches[$i], $val);
				}
			}
		}

		return $array;

	}

	public function updateProvider()
	{
		// if provider already set, update it's data
		if ( ! is_null($this->provider) ) {
			if ( ! is_null($this->attributes) ) {
				if ( isset($this->provider['iframe']) ) {
					$this->provider['iframe'] = array_replace($this->provider['iframe'], $this->attributes);
				}
				if ( isset($this->provider['object']) && isset($this->provider['object']['attributes']) ) {
					$this->provider['object']['attributes'] = array_replace($this->provider['object']['attributes'], $this->attributes);
				}
				if ( isset($this->provider['object']) && isset($this->provider['object']['embed']) ) {
					$this->provider['object']['embed'] = array_replace($this->provider['object']['embed'], $this->attributes);
				}
			}

			if ( ! is_null($this->params) ) {
				if ( isset($this->provider['object']) && isset($this->provider['object']['params']) ) {
					$this->provider['object']['params'] = array_replace($this->provider['object']['params'], $this->params);
				}
			}
		}

	}

	// matches - array of matched strings
	// provider that we matched
	public function forgeIframe()
	{
		// check if we have an iframe creation array
		if ( ! isset($this->provider) && ! isset($this->provider['render']['iframe']) ) {
			return false;
		}

		// start iframe tag
		$iframe = '<iframe';

		foreach ($this->provider['render']['iframe'] as $attribute => $val) {
			$iframe .= sprintf(' %s="%s"', $attribute, $val);
		}

		// close iframe
		$iframe .='></iframe>';

		return $iframe;

	}

	public function forgeObject()
	{
		// check if we have an object creation array
		if ( isset($this->provider) && ! isset($this->provider['render']['object']) ) {
			return false;
		}

		// start object tag
		$object = '<object';

		foreach ($this->provider['object']['attributes'] as $attribute => $val) {
			$object .= sprintf(' %s="%s"', $attribute, $val);
		}
		$object .= '>';

		// create params
		if ( isset($this->provider['object']['params']) ) {
			foreach ($this->provider['object']['params'] as $param => $val) {
				$object .= sprintf('<param name="%s" value="%s"></param>', $param, $val);
			}
		}

		// create embed
		if ( isset($this->provider['object']['embed']) ) {
			$object .= '<embed';
			// embed can have same attributes as object itself (height, width etc)
			foreach ($this->provider['object']['embed'] as $ettribute => $val) {
				$val = ( is_bool($val) && $val ? 'true' : 'false' );
				$object .= sprintf(' %s="%s"', $attribute, $val);
			}
			$object .= '></embed>';
		}

		$object .= '</object>';

		return $object;

	}

	public function getHtmlCode()
	{
		if ($iframe = $this->forgeIframe()) return $iframe;
		if ($object = $this->forgeObject()) return $object;
	}

	public function getIframeCode()
	{
		return $this->forgeIframe();
	}

	public function getObjectCode()
	{
		return $this->forgeObject();
	}

	public function setProviders($providers)
	{
		$this->providers = $providers;
	}

	public function getProvider()
	{
		return $this->provider;
	}

}
