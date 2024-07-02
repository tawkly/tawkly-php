<?php

namespace Unswer;

use Unswer\Exceptions\UnswerException;
use Rakit\Validation\Validator;

class BaseClient
{
    /**
     * API Key
     * @var string
     */
    protected static $apiKey;

    /**
     * App ID
     * @var string
     */
    protected static $appId;

    /**
     * HTTP Client
     * @var Http
     */
    protected static $http;

    /**
     * Validator
     * @var Validator
     */
    protected static $validator;

    /**
     * @param string $apiKey
     * @param string $appId
     * @param mixed $config
     */
    public function __construct($apiKey = null, $appId = null, $config = [])
    {
        $apiKey = self::$apiKey ?: ($apiKey ?: getenv('UNSWER_API_KEY'));
        $appId = self::$appId ?: ($appId ?: getenv('UNSWER_APP_ID'));

        if (empty($apiKey) || empty($appId)) {
            throw new UnswerException('API Key and App ID must be configured');
        }

        self::$apiKey = $apiKey;
        self::$appId = $appId;
        self::$http ??= new Http($apiKey, $config);
        self::$validator ??= new Validator();
    }
}
