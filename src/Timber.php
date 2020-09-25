<?php

namespace Rebing\Timber;

use GuzzleHttp\Client;
use Rebing\Timber\Exceptions\TimberException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Communicates with the Timber logger API (https://api-docs.timber.io/)
 *
 * Class Timber
 * @package Rebing\Timber
 */
class Timber
{
    const SERVER_URI = 'https://logs.timber.io/';

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    private $requestUri;
    private $apiKey;
    private $sourceId;
    private $enabled = true;

    /**
     * Timber constructor.
     * @throws TimberException
     */
    public function __construct()
    {
        $this->requestUri = self::SERVER_URI;
        $this->apiKey     = config('timber.api_key');
        $this->sourceId   = config('timber.source_id');
        $this->enabled    = config('timber.enabled');


        if (is_null($this->apiKey)) {
            throw new TimberException('API key not set!');
        }
    }

    protected function doRequest(string $method, string $endpoint, array $options = [])
    {
        if (!$this->enabled) {
            return '';
        }

        if ($this->sourceId) {
            $baseURI = $this->requestUri."sources/{$this->sourceId}/";
        }
        
        $client = new Client([
            'base_uri' => $baseURI,
            'headers'  => [
                'Authorization' => 'Basic ' . base64_encode($this->apiKey),
            ],
        ]);

        try {
            $res = $client->request($method, $endpoint, $options);
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }

        return $res->getBody()->getContents();
    }
}
