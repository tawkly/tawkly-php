<?php

namespace Unswer;

use GuzzleHttp\Client;

class Http
{
    /**
     * API Hostname
     * @var string
     */
    protected static $host = 'http://localhost:3333/api';

    /**
     * API Version
     * @var string
     */
    protected static $version = 'v1';

    /**
     * API Key
     * @var string
     */
    protected static $apiKey;

    /**
     * HTTP Client
     * @var Client
     */
    protected $client;

    public function __construct($apiKey)
    {
        self::$client ??= new Client([
            'http_errors' => false,
            'base_uri' => self::$host . "/" . self::$version,
        ]);
        self::$apiKey = self::$apiKey ?: ($apiKey ?: getenv('UNSWER_API_KEY'));
    }

    /**
     * Make a GET requests to Unswer API
     * @param string $endpoint
     * @param array $query
     * @return mixed
     */
    protected function get($endpoint, $query = [])
    {
        $response = self::$client->get($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$apiKey,
                'Accept' => 'application/json',
            ],
            'query' => $query,
        ]);

        $json = $response->getBody()->getContents();
        return json_decode($json);
    }

    /**
     * Make a POST requests to Unswer API
     * @param string $endpoint
     * @param array body
     * @return mixed
     */
    protected function post($endpoint, $body = [])
    {
        $response = self::$client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$apiKey,
                'Accept' => 'application/json',
            ],
            'json' => $body,
        ]);

        $json = $response->getBody()->getContents();
        return json_decode($json);
    }

    /**
     * Make a PUT requests to Unswer API
     * @param string $endpoint
     * @param array body
     * @return mixed
     */
    protected function put($endpoint, $body = [])
    {
        $response = self::$client->put($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$apiKey,
                'Accept' => 'application/json',
            ],
            'json' => $body,
        ]);

        $json = $response->getBody()->getContents();
        return json_decode($json);
    }

    /**
     * Make a DELETE requests to Unswer API
     * @param string $endpoint
     * @param array body
     * @return mixed
     */
    protected function delete($endpoint, $body = [])
    {
        $response = self::$client->delete($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$apiKey,
                'Accept' => 'application/json',
            ],
            'json' => $body,
        ]);

        $json = $response->getBody()->getContents();
        return json_decode($json);
    }

    /**
     * Upload file to Unswer API
     * @param string $endpoint
     * @param array body
     * @return mixed
     */
    protected function multipart($endpoint, $body = [])
    {
        if (!count($body)) {
            return null;
        }

        $response = self::$client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$apiKey,
                'Accept' => 'application/json',
            ],
            'multipart' => $body,
        ]);

        $json = $response->getBody()->getContents();
        return json_decode($json);
    }
}
