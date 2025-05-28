<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tawkly\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;

class HttpTest extends TestCase
{
    protected Http $http;

    /**
     * @var \Mockery\LegacyMockInterface&\Mockery\MockInterface&object
     */
    protected $clientMock;

    /**
     * @var \Mockery\LegacyMockInterface&\Mockery\MockInterface&object
     */
    protected $responseMock;

    /**
     * @var \Mockery\LegacyMockInterface&\Mockery\MockInterface&object
     */
    protected $streamMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientMock = Mockery::mock(Client::class);
        $this->responseMock = Mockery::mock(Response::class);
        $this->streamMock = Mockery::mock('Psr\Http\Message\StreamInterface');

        Http::$client = $this->clientMock;
        Http::$apiKey = 'test_api_key';
        Http::$host = 'https://unswer.id/api';

        $this->http = new Http('test_api_key');
    }

    public function testGetMethodSuccess()
    {
        $endpoint = 'test-endpoint';
        $query = ['param' => 'value'];
        $expectedResponse = (object) ['data' => 'test'];

        $this->clientMock->shouldReceive('get')
            ->once()
            ->with($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer test_api_key',
                    'Accept' => 'application/json',
                ],
                'query' => $query,
            ])
            ->andReturn($this->responseMock);

        $this->responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn($this->streamMock);

        $this->streamMock->shouldReceive('getContents')
            ->once()
            ->andReturn(json_encode($expectedResponse));

        $response = $this->http->get($endpoint, $query);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testPostMethodSuccess()
    {
        $endpoint = 'test-endpoint';
        $body = ['key' => 'value'];
        $expectedResponse = (object) ['data' => 'test'];

        $this->clientMock->shouldReceive('post')
            ->once()
            ->with($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer test_api_key',
                    'Accept' => 'application/json',
                ],
                'json' => $body,
            ])
            ->andReturn($this->responseMock);

        $this->responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn($this->streamMock);

        $this->streamMock->shouldReceive('getContents')
            ->once()
            ->andReturn(json_encode($expectedResponse));

        $response = $this->http->post($endpoint, $body);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testPutMethodSuccess()
    {
        $endpoint = 'test-endpoint';
        $body = ['key' => 'value'];
        $expectedResponse = (object) ['data' => 'test'];

        $this->clientMock->shouldReceive('put')
            ->once()
            ->with($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer test_api_key',
                    'Accept' => 'application/json',
                ],
                'json' => $body,
            ])
            ->andReturn($this->responseMock);

        $this->responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn($this->streamMock);

        $this->streamMock->shouldReceive('getContents')
            ->once()
            ->andReturn(json_encode($expectedResponse));

        $response = $this->http->put($endpoint, $body);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteMethodSuccess()
    {
        $endpoint = 'test-endpoint';
        $body = ['key' => 'value'];
        $expectedResponse = (object) ['data' => 'test'];

        $this->clientMock->shouldReceive('delete')
            ->once()
            ->with($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer test_api_key',
                    'Accept' => 'application/json',
                ],
                'json' => $body,
            ])
            ->andReturn($this->responseMock);

        $this->responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn($this->streamMock);

        $this->streamMock->shouldReceive('getContents')
            ->once()
            ->andReturn(json_encode($expectedResponse));

        $response = $this->http->delete($endpoint, $body);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testNullableMultipartMethod()
    {
        $response = $this->http->multipart('test-endpoint', []);
        $this->assertNull($response);
    }

    public function testMultipartMethodSuccess()
    {
        $endpoint = 'test-endpoint';
        $body = ['key' => 'value'];
        $expectedResponse = (object) ['data' => 'test'];

        $this->clientMock->shouldReceive('post')
            ->once()
            ->with($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer test_api_key',
                    'Accept' => 'application/json',
                ],
                'multipart' => $body,
            ])
            ->andReturn($this->responseMock);

        $this->responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn($this->streamMock);

        $this->streamMock->shouldReceive('getContents')
            ->once()
            ->andReturn(json_encode($expectedResponse));

        $response = $this->http->multipart($endpoint, $body);

        $this->assertEquals($expectedResponse, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
