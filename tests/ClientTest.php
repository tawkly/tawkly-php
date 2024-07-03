<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Unswer\Client;
use Unswer\Exceptions\UnswerException;
use Unswer\Services\ContactService;
use Unswer\Services\MessageService;

class ClientTest extends TestCase
{
    public function testConstructWithoutApiKeyAndAppId()
    {
        $this->expectException(UnswerException::class);
        new Client(null, null, []);
    }

    /**
     * @depends testConstructWithoutApiKeyAndAppId
     */
    public function testConstructWithApiKeyAndAppId()
    {
        $apiKey = 'test_api_key';
        $appId = 'test_app_id';

        $client = new Client($apiKey, $appId, []);

        $this->assertInstanceOf(ContactService::class, $client->contacts());
        $this->assertInstanceOf(MessageService::class, $client->messages());
    }
}
