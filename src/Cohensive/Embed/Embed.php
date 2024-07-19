<?php
namespace Cohensive\Embed;

use Closure;

class Embed
{
    /**
     * Embed configs.
     *
     * @var array
     */
    protected $config;

    /**
     * Current url protocol.
     *
     * @var string
     */
    protected $protocol;

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
     * Config with all available providers.
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
     * Cached version of config provider.
     *
     * @var array
     */
    protected $cachedProvider;

    /**
     * List of matches that will be set on provider during parse run.
     *
     * @var array
     */
    protected $matches;

    /**
     * List of initial matches that will be set on provider during parse run.
     *
     * @var array
     */
    protected $cachedMatches;

    /**
     * Video timestmap if available.
     *
     * @var string
     */
    protected $timestamp;

    /**
     * AMP mode.
     * https://www.ampproject.org/
     *
     * @var bool
     */
    protected $ampMode;

    /**
     * Create Embed instance.
     *
     * @param  string  $url
     * @param  mixed   $options
     * @return void
     */
    public function __construct($url = null, $options = null, array $config = null)
    {
        if (! is_null($url)) {
            $this->setUrl($url);
        }

        if (! is_null($options)) {
            $this->attributes = isset($options['attributes']) ? $options['attributes'] : null;
            $this->params = isset($options['params']) ? $options['params'] : null;
        }

        if (! is_null($config)) {
            $this->setConfig($config);
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
            $this->cachedProvider = null;
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
                    if ($this->findProviderMatch($provider['url'], $provider)) {
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
            $this->cachedMatches = $matches;
            $this->cachedProvider = $provider;
            $this->updateProvider();
            return true;
        }
        return false;
    }

    /**
     * Get protocol for current url and provider.
     *
     * @return void
     */
    protected function parseProtocol($matches)
    {
        if ($matches[1] == 'http://' || $matches[1] == 'https://') {
            $protocol = $matches[1];
            array_splice($this->matches, 1, 1);
        } else {
            $protocol = 'http://';
        }

        if (! $this->url || ! $this->cachedProvider) {
            throw new \Exception('Cannot detect protocol if URL or provider were not set.');
        }

        // If provider supports SSL - use it. There's no excuse in not using it.
        if ($this->cachedProvider['ssl']) {
            $this->protocol = 'https';
        } elseif ($protocol === 'https://') {
            // If provider doesn't have SSL flag, but url contains SSL - use it.
            $this->protocol = 'https';
        } else {
            $this->protocol = 'http';
        }
    }

    /**
     * Alias for parseData method.
     *
     * @return void
     */
    public function fetchData()
    {
        return $this->parseData();
    }

    /**
     * Get remote data if available.
     *
     * @return \Cohensive\Embed\Embed
     */
    public function parseData()
    {
        if (isset($this->provider['dataCallback'])) {
            if ($this->provider['dataCallback'] instanceof Closure) {
                $this->provider['data'] = $this->provider['dataCallback']($this->getProvider());
            } elseif (is_string($this->provider['dataCallback'])) {
                $this->executeFetchingClassOrFunction($this->provider['dataCallback']);
            }
        }
        return $this;
    }

    /**
     * Executes class method or function which handles remote data fetching.
     *
     * @param string $target
     * @return void
     */
    protected function executeFetchingClassOrFunction($target)
    {
        $provider = $this->getProvider();
        $parts = explode('@', $target);

        if (count($parts) === 1) {
            $this->provider['data'] = $parts[0]($provider, $this->config);
        } elseif (count($parts) === 2) {
            $class = new $parts[0]($this, $this->config);
            $this->provider['data'] = $class->{$parts[1]}($provider, $this->config);
        }
    }

    /**
     * Parse found provider and replace {x} parts with parsed code.
     *
     * @param  array  $array
     * @param  array  $matches
     * @return array  $array
     */
    protected function parseProvider(&$array, $matches)
    {
        // Check if we have an iframe creation array.
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $array[$key] = $this->parseProvider($val, $matches);
            } else {
                $array[$key] = str_replace('{protocol}', $this->protocol, $array[$key]) . $this->timestamp;
                for ($i=1; $i<count($matches); $i++) {
                    $array[$key] = str_replace('{'.$i.'}', $matches[$i], $array[$key]);
                }
            }
        }

        return $array;
    }

    /**
     * Parse found provider and add timestamp to render sources.
     *
     * @return array $array
     */
    protected function parseTimestamp(&$provider)
    {
        if (isset($provider['timestamp'])) {
            if (preg_match('~'.$provider['timestamp'].'~imu', $this->url, $matches)) {
                unset($matches[0]);
                $multipliers = [3600, 60, 1];
                $timestamp = 0;
                foreach (array_values($matches) as $key => $match) {
                    $timestamp += (int) $match * $multipliers[$key];
                }
                $this->timestamp = $provider['timestampParam'] . $timestamp;
            }
        }
    }

    /**
     * Update provider if set.
     *
     * @return void
     */
    public function updateProvider()
    {
        if ($this->cachedProvider) {
            $this->matches = $this->cachedMatches;
            $this->provider = $this->cachedProvider;
            $this->parseProtocol($this->matches);
            $this->parseTimestamp($this->provider);
            $this->parseProvider($this->provider['info'], $this->matches);
            $this->parseProvider($this->provider['render'], $this->matches);

            if (isset($this->attributes['width']) && ! isset($this->attributes['height'])) {
                $this->attributes['height'] = (int) $this->attributes['width']/ (double) $this->provider['render']['sizeRatio'];
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
            $tag = $this->ampMode ? 'amp-iframe' : 'iframe';
            // Start iframe tag.
            $iframe = "<$tag";

            foreach ($this->provider['render']['iframe'] as $attribute => $val) {
                $iframe .= sprintf(' %s="%s"', $attribute, $val);
            }

            // Close iframe tag.
            $iframe .= "></$tag>";

            $iframe .= $this->forgeScript();

            return $iframe;
        }
    }

    /**
     * Generate wrapped iframe for embed if required and available.
     *
     * @return string
     */
    public function forgeWrappedIframe()
    {
        // Check if we have an iframe creation array.
        if (!$this->ampMode
            && $this->provider
            && isset($this->provider['render']['wrappedIframe'])
            && isset($this->provider['render']['wrapper'])
        ) {
            $tag = 'iframe';
            // Start iframe tag.
            $iframe = "<$tag";

            foreach ($this->provider['render']['wrappedIframe'] as $attribute => $val) {
                $iframe .= sprintf(' %s="%s"', $attribute, $val);
            }

            // Close iframe tag.
            $iframe .= "></$tag>";

            $iframe .= $this->forgeScript();

            $wrapper = $this->provider['render']['wrapper'];

            return $wrapper['start'] . $iframe . $wrapper['end'];
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
                foreach ($this->provider['render']['object']['embed'] as $attribute => $val) {
                    $val = ( is_bool($val) && $val ? ($val ? 'true' : 'false') : $val );
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
            $tag = $this->ampMode ? 'amp-video' : 'video';

            // Start iframe tag.
            $video = "<$tag";

            foreach ($this->provider['render']['video'] as $attribute => $val) {
                if (! is_array($val)) {
                    $video .= sprintf(' %s="%s"', $attribute, $val);
                }
            }
            // Close start of video tag.
            $video .='>';

            // Add inner elements.
            $video .= $this->forgeInnerElements($this->provider['render']['video'], true);

            // Wrap video tag.
            $video .= "</$tag>";

            $video .= $this->forgeScript();

            return $video;
        }
    }

    private function forgeInnerElements($attributes = [], $initial = false, $tag = null)
    {
        $output = '';
        // Add inner elements.
        $l = count($attributes);
        $i = 0;
        foreach ($attributes as $key => $val) {
            $i++;
            if (is_array($val) && ! is_numeric($key)) {
                $output .= $this->forgeInnerElements($val, false, $key);
            } elseif (is_array($val)) {
                $output .=  "<$tag " . $this->forgeInnerElements($val) . "></$tag>";
            } elseif (! $initial) {
                $output .= "$key=\"$val\"";
                if ($i !== $l) $output .= ' ';
            }
        }
        return $output;
    }

    /**
     * Excplicitly set configs.
     *
     * @param  array  $config
     * @return \Cohensive\Embed\Embed
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Excplicitly set url.
     *
     * @param  string  $url
     * @return \Cohensive\Embed\Embed
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Return url saved in current embed instance.
     *
     * @return string
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
     * @return \Cohensive\Embed\Embed
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
     * @return \Cohensive\Embed\Embed
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
        $prevAmpMode = $this->ampMode;
        $this->disableAmpMode();
        if ($html = $this->forgeIframe()) return $html;
        if ($html = $this->forgeVideo()) return $html;
        if ($html = $this->forgeObject()) return $html;
        $this->ampMode = $prevAmpMode;
    }

    /**
     * Generate AMP html code for embed.
     *
     * @return string
     */
    public function getAmpHtml()
    {
        $prevAmpMode = $this->ampMode;
        $this->enableAmpMode();
        if ($html = $this->forgeIframe()) return $html;
        if ($html = $this->forgeVideo()) return $html;
        if ($html = $this->forgeObject()) return $html;
        $this->ampMode = $prevAmpMode;
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
     * @return \Cohensive\Embed\Embed
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

    /**
     * Sets AMP mode.
     *
     * @param bool $ampMode
     * @return void
     */
    public function setAmpMode($ampMode)
    {
        $this->ampMode = $ampMode;
    }

    /**
     * Enables AMP mode.
     *
     * @return void
     */
    public function enableAmpMode()
    {
        $this->ampMode = true;
    }

    /**
     * Disables AMP mode.
     *
     * @return void
     */
    public function disableAmpMode()
    {
        $this->ampMode = false;
    }
}
