<?php
namespace Cohensive\Embed;

class Embed
{

	/**
	 * Current possible provider url.
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * List of attributes to set on object/iframe.
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * List of params to set on object code.
	 *
	 * @var array
	 */
	protected $params;

	/**
	 * List of available providers.
	 *
	 * @var array
	 */
	protected $providers;

	/**
	 * Parsed provider that has been found based on input url.
	 *
	 * @var array
	 */
	protected $provider;

	/**
	 * List of matches that will be set on provider during parse run.
	 *
	 * @var array
	 */
	protected $matches;

	/**
	 * Create Embed instance.
	 *
	 * @param  string  $url
	 * @param  mixed  $config
	 * @return void
	 */
	public function __construct($url = null, $config = null)
	{
		if (! is_null($url)) {
			$this->url = $url;
		}

		if (! is_null($config)) {
			$this->attributes = isset($config['attributes']) ? $config['attributes'] : null;
			$this->params = isset($config['params']) ? $config['params'] : null;
		}
	}

	/**
	 * Parse given url.
	 *
	 * @return mixed
	 */
	public function parseUrl()
	{
		if (! is_null($this->url)) {
			// Reset provider before parsing new url.
			$this->provider = null;
			foreach ($this->providers as $provider) {
				if ( is_array($provider['url']) ) {
					// Multiple urls to check against.
					foreach ($provider['url'] as $pattern) {
						if ($this->findProviderMatch($pattern, $provider)) {
							return $this;
						}
					}

				} else {
					if ($this->findProviderMatch($pattern, $provider)) {
						return $this;
					}
				}
			}
			return false;
		} else {
			return false;
		}
	}

	/**
	 * Find a url match in provider pattern
	 *
	 * @return bool
	 */
	protected function findProviderMatch($pattern, $provider)
	{
		if (preg_match('~'.$pattern.'~imu', $this->url, $matches)) {
			$this->matches = $matches;
			$this->provider = $provider;
			$this->parseProvider($this->provider['info'], $matches);
			$this->parseProvider($this->provider['render'], $matches);
			$this->updateProvider();
			return true;
		}
		return false;
	}

	/**
	 * Get remote data if available.
	 *
	 * @return Cohensive\Embed\Embed
	 */
	public function parseData()
	{
		if (isset($this->provider['dataCallback'])) {
			$this->provider['data'] = $this->provider['dataCallback']($this);
		}
		return $this;
	}


	/**
	 * Parse found provider and replace {x} parts with parsed code.
	 *
	 * @param  array  $array
	 * @param  array  $matches
	 * @return array  $array
	 */
	private function parseProvider(&$array, $matches)
	{
		// Check if we have an iframe creation array.
		foreach ($array as $key => $val) {
			if (is_array($val)) {
				$array[$key] = $this->parseProvider($val, $matches);
			} else {
				for ($i=1; $i<count($matches); $i++) {
					$array[$key] = str_replace('{'.$i.'}', $matches[$i], $array[$key]);
				}
			}
		}

		return $array;
	}

	/**
	 * Update provider if set.
	 *
	 * @return void
	 */
	public function updateProvider()
	{
		// If provider already set, update some of its attributes and params.
		if ($this->provider) {
			if (isset($this->attributes['width']) && ! isset($this->attributes['height'])) {
				$this->attributes['height'] = $this->attributes['width']/$this->provider['render']['sizeRatio'];
			}

			if (! is_null($this->attributes)) {
				if (isset($this->provider['render']['video'])) {
					$this->provider['render']['video'] = array_replace($this->provider['render']['video'], $this->attributes);
				}
				if (isset($this->provider['render']['iframe'])) {
					$this->provider['render']['iframe'] = array_replace($this->provider['render']['iframe'], $this->attributes);
				}
				if (isset($this->provider['render']['object']) && isset($this->provider['render']['object']['attributes'])) {
					$this->provider['render']['object']['attributes'] = array_replace($this->provider['render']['object']['attributes'], $this->attributes);
				}
				if (isset($this->provider['render']['object']) && isset($this->provider['render']['object']['embed'])) {
					$this->provider['render']['object']['embed'] = array_replace($this->provider['render']['object']['embed'], $this->attributes);
				}
			}

			if (! is_null($this->params)) {
				if (isset($this->provider['render']['object']) && isset($this->provider['render']['object']['params'])) {
					$this->provider['render']['object']['params'] = array_replace($this->provider['render']['object']['params'], $this->params);
				}
			}
		}
	}

	/**
	 * Generate script for embed if required and available. Usually required for certain iframes.
	 *
	 * @return string
	 */
	public function forgeScript()
	{
		// check if we have a script creation array
		if ($this->provider && isset($this->provider['render']['script'])) {
			// Start script tag.
			$script = '<script';

			foreach ($this->provider['render']['script'] as $attribute => $val) {
				$script .= sprintf(' %s="%s"', $attribute, $val);
			}

			// Close script tag.
			$script .='></script>';

			return $script;
		}
	}

	/**
	 * Generate iframe for embed if required and available.
	 *
	 * @return string
	 */
	public function forgeIframe()
	{
		// Check if we have an iframe creation array.
		if ($this->provider && isset($this->provider['render']['iframe'])) {
			// Start iframe tag.
			$iframe = '<iframe';

			foreach ($this->provider['render']['iframe'] as $attribute => $val) {
				$iframe .= sprintf(' %s="%s"', $attribute, $val);
			}

			// Close iframe tag.
			$iframe .='></iframe>';

			$iframe .= $this->forgeScript();

			return $iframe;
		}
	}

	/**
	 * Generate object for embed if required and available.
	 *
	 * @return string
	 */
	public function forgeObject()
	{
		// Check if we have an object creation array.
		if ($this->provider && isset($this->provider['render']['object'])) {
			// Start object tag.
			$object = '<object';

			foreach ($this->provider['render']['object']['attributes'] as $attribute => $val) {
				$object .= sprintf(' %s="%s"', $attribute, $val);
			}
			$object .= '>';

			// Create params.
			if ( isset($this->provider['render']['object']['params']) ) {
				foreach ($this->provider['render']['object']['params'] as $param => $val) {
					$object .= sprintf('<param name="%s" value="%s"></param>', $param, $val);
				}
			}

			// Create embed.
			if ( isset($this->provider['render']['object']['embed']) ) {
				$object .= '<embed';
				// embed can have same attributes as object itself (height, width etc)
				foreach ($this->provider['render']['object']['embed'] as $ettribute => $val) {
					$val = ( is_bool($val) && $val ? 'true' : 'false' );
					$object .= sprintf(' %s="%s"', $attribute, $val);
				}
				$object .= '></embed>';
			}

			// Close object tag.
			$object .= '</object>';

			$object .= $this->forgeScript();

			return $object;
		}
	}

	/**
	 * Generate HTML5 video tag if required and available.
	 *
	 * @return string
	 */
	public function forgeVideo()
	{
		// Check if we have a video creation array.
		if ($this->provider && isset($this->provider['render']['video'])) {
			// Start iframe tag.
			$video = '<video';

			foreach ($this->provider['render']['video'] as $attribute => $val) {
				$video .= sprintf(' %s="%s"', $attribute, $val);
			}

			// Close video tag.
			$video .='></video>';

			$video .= $this->forgeScript();

			return $video;
		}
	}

	/**
	 * Excplicitly set url.
	 *
	 * @param  string  $url
	 * @return void
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * Return url saved in current embed instance.
	 *
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Excplicitly set embed attributes.
	 *
	 * @param  string  $key
	 * @param  mixed  $val
	 * @return void
	 */
	public function setAttribute($key, $val = null)
	{
		if (is_array($key)) {
			foreach ($key as $k => $val) {
				$this->attributes[$k] = $val;
			}
		} else {
			$this->attributes[$key] = $val;
		}

		// If provider already set, update it's data.
		$this->updateProvider();

		return $this;
	}

	/**
	 * Return attributes.
	 *
	 * @return mixed
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Excplicitly set embed params.
	 *
	 * @param  string  $key
	 * @param  mixed  $val
	 * @return void
	 */
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

		return $this;
	}

	/**
	 * Return params.
	 *
	 * @return mixed
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Generate html code for embed.
	 *
	 * @return string
	 */
	public function getHtml()
	{
		if ($html = $this->forgeIframe()) return $html;
		if ($html = $this->forgeVideo()) return $html;
		if ($html = $this->forgeObject()) return $html;
	}

	/**
	 * Alias for iframe forge method.
	 *
	 * @return string
	 */
	public function getIframe()
	{
		return $this->forgeIframe();
	}

	/**
	 * Alias for object forge method.
	 *
	 * @return string
	 */
	public function getObject()
	{
		return $this->forgeObject();
	}

	/**
	 * Alias for video forge method.
	 *
	 * @return string
	 */
	public function getVideo()
	{
		return $this->forgeVideo();
	}

	/**
	 * Set up new list of providers.
	 *
	 * @param  array  $aproviders
	 * @return void
	 */
	public function setProviders(array $providers)
	{
		$this->providers = $providers;

		return $this;
	}

	/**
	 * Get list of providers.
	 *
	 * @return mixed
	 */
	public function getProviders()
	{
		return $this->providers;
	}

	/**
	 * Get current provider.
	 *
	 * @return array
	 */
	public function getProvider()
	{
		return json_decode(json_encode($this->provider));
	}

}
