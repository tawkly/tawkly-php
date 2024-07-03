<?php

namespace Unswer;

use GuzzleHttp\Client;
use stdClass;

class Http
{
    public static string $host;
    public static string $version = 'v1';
    public static string $apiKey = '';
    public static Client $client;

    public function __construct(string $apiKey, array $config = [])
    {
        self::$host ??= $config['host'] ?? 'https://unswer.id/api';
        self::$apiKey = self::$apiKey ?: ($apiKey ?: getenv('UNSWER_API_KEY'));

        unset($config['host']);

        $base = self::$host . '/' . self::$version . '/';
        $config = array_merge_recursive(['http_errors' => false, 'base_uri' => $base], $config);

        self::$client ??= new Client($config);
    }

    public function get(string $endpoint, array $query = []): stdClass
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

    public function post(string $endpoint, array $body = []): stdClass
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

    public function put(string $endpoint, array $body = []): stdClass
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

    public function delete(string $endpoint, array $body = []): stdClass
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

    public function multipart(string $endpoint, array $body = []): ?stdClass
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
