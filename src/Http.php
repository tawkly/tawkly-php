<?php

namespace Unswer;

use GuzzleHttp\Client;

class Http
{
    /**
     * API Hostname
     * @var string
     */
    public static $host;

    /**
     * API Version
     * @var string
     */
    public static $version = 'v1';

    /**
     * API Key
     * @var string
     */
    public static $apiKey;

    /**
     * HTTP Client
     * @var Client
     */
    public static $client;

    /**
     * @param string $apiKey
     * @param mixed $config
     */
    public function __construct($apiKey, $config = [])
    {
        self::$host ??= $config['host'] ?? 'https://unswer.id/api';
        self::$apiKey = self::$apiKey ?: ($apiKey ?: getenv('UNSWER_API_KEY'));

        unset($config['host']);

        $base = self::$host . '/' . self::$version . '/';
        $config = array_merge_recursive(['http_errors' => false, 'base_uri' => $base], $config);

        self::$client ??= new Client($config);
    }

    /**
     * Make a GET requests to Unswer API
     * @param string $endpoint
     * @param array $query
     * @return mixed
     */
    public function get($endpoint, $query = [])
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
    public function post($endpoint, $body = [])
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
    public function put($endpoint, $body = [])
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
    public function delete($endpoint, $body = [])
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
    public function multipart($endpoint, $body = [])
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
