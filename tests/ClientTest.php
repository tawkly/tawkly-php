<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tawkly\Client;
use Tawkly\Exceptions\TawklyException;
use Tawkly\Services\ContactService;
use Tawkly\Services\MessageService;
use Tawkly\Services\TemplateService;

class ClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->resetPrivateProperties('apiKey');
        $this->resetPrivateProperties('appId');
    }

    private function resetPrivateProperties($propertyName)
    {
        $reflection = new \ReflectionClass(Client::class);
        $property = $reflection->getProperty($propertyName);

        $property->setAccessible(true);
        $property->setValue(null, null);
    }

    public function testConstructWithoutApiKeyAndAppId()
    {
        $this->expectException(TawklyException::class);
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
        $this->assertInstanceOf(TemplateService::class, $client->templates());
    }
}
