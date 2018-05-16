<?php
namespace Cohensive\Embed;

use Exception;

class DataFetcher
{
    /**
     * @var Embed
     */
    protected $embed;

    /**
     * @var array
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param Embed $embed
     * @param array $config
     */
    public function __construct(Embed $embed, array $config = null)
    {
        $this->embed = $embed;
        $this->config = $config;
    }

    /**
     * Fetches data related to the video.
     *
     * @return array
     * @throws Exceptions\MissingConfigurationException
     */
    public function fetchYoutube($provider)
    {
        if (!$this->config || ($this->config && !$this->config['google_api_key'])) {
            throw new Exceptions\MissingConfigurationException('Missing Google API key.');
        }

        $response = null;
        $url = $provider->info->dataUrl . '&key=' . $this->config['google_api_key'];

        try {
            $response = json_decode(file_get_contents($url))->items[0];
        } catch (Exception $e) {
        }

        if (!$response) {
            return null;
        }

        return [
            'title' => $response->snippet->title,
            'description' => $response->snippet->description,
            'created_at' => $response->snippet->publishedAt,
            'image' => [
                'small' => $response->snippet->thumbnails->default->url,
                'medium' => $response->snippet->thumbnails->medium->url,
                'large' => $response->snippet->thumbnails->high->url,
                'max' => $response->snippet->thumbnails->maxres->url,
            ],
            'full' => $response,
        ];
    }

    /**
     * Undocumented function
     *
     * @param [type] $provider
     * @return void
     */
    public function fetchVimeo($provider)
    {
        $response = null;
        $url = $provider->info->dataUrl;

        try {
            $response = json_decode(file_get_contents($url))[0];
        } catch (Exception $e) {
        }

        if (!$response) {
            return null;
        }

        return [
            'title'  => $response->title,
            'description' => $response->description,
            'created_at'  => $response->upload_date,
            'image' => [
                'small'  => $response->thumbnail_small,
                'medium' => $response->thumbnail_medium,
                'large'  => $response->thumbnail_large,
                'max'  => $response->thumbnail_large,
            ],
            'full' => $response,
        ];
    }

}
