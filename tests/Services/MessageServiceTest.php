<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Unswer\Services\MessageService;
use Unswer\Http;
use Mockery;
use ReflectionClass;
use Unswer\Exceptions\UnswerException;
use Unswer\Models\Message;
use Unswer\Models\Room;

class MessageServiceTest extends TestCase
{
    /**
     * @var MessageService
     */
    protected $messageService;

    /**
     * @var \Mockery\LegacyMockInterface&\Mockery\MockInterface&object
     */
    protected $httpMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpMock = Mockery::mock(Http::class);
        $this->messageService = new MessageService('test_api_key', 'test_app_id');

        $reflection = new ReflectionClass(MessageService::class);
        $httpProperty = $reflection->getProperty('http');
        $httpProperty->setAccessible(true);
        $httpProperty->setValue($this->messageService, $this->httpMock);
    }

    public function testRetrieveRoomListsWithInvalidParams()
    {
        $this->expectException(UnswerException::class);
        $this->messageService->all(1, 80);
    }

    public function testRetrieveRoomListsSuccess()
    {
        $response = (object) [
            'data' => [
                (object) [
                    'id' => '83facc8c-11c5-11ef-8e7a-00155d34f1bf',
                    'phone' => 6283812345678,
                    'tag' => 'John Doe',
                    'is_blocked' => false,
                    'lastest' => (object) [
                        'id' => 'c8009895-76bf-4bc1-a7c5-3d55b95aa577',
                        'type' => 'text',
                        'text' => (object) [
                            'body' => 'lorem ipsum dolor sit amet',
                        ],
                        'attachment' => NULL,
                        'status' => 'sent',
                        'is_me' => false,
                        'received_at' => '2024-07-02T15:09:23.000+07:00',
                    ],
                ],
            ],
            'meta' => (object) [
                'total' => 1,
                'limit' => 10,
                'current_page' => 1,
                'first_page' => 1,
                'previous_page' => null,
                'next_page' => null,
                'last_page' => 1,
            ]
        ];

        $this->httpMock->shouldReceive('get')
            ->once()
            ->with('messages/test_app_id', ['page' => 1, 'limit' => 10])
            ->andReturn($response);

        $pager = $this->messageService->all(1, 10);
        $this->assertInstanceOf(Room::class, $pager->items()->first());
    }

    public function testRetrieveMessageListsWithInvalidParams()
    {
        $this->expectException(UnswerException::class);
        $this->messageService->list('83facc8c-11c5-11ef-8e7a-00155d34f1bf', 1, 80);
    }

    public function testRetrieveMessageListsSuccess()
    {
        $response = (object) [
            'data' => [
                (object) [
                    'id' => 'c8009895-76bf-4bc1-a7c5-3d55b95aa577',
                    'type' => 'text',
                    'text' => [
                        'body' => 'lorem ipsum dolor sit amet'
                    ],
                    'attachment' => null,
                    'status' => 'sent',
                    'is_me' => false,
                    'received_at' => '2024-07-02T15:09:23.000+07:00',
                ]
            ],
            'meta' => (object) [
                'total' => 1,
                'limit' => 10,
                'current_page' => 1,
                'first_page' => 1,
                'previous_page' => null,
                'next_page' => null,
                'last_page' => 1,
            ]
        ];

        $this->httpMock->shouldReceive('get')
            ->once()
            ->with('messages/test_app_id/83facc8c-11c5-11ef-8e7a-00155d34f1bf',  ['page' => 1, 'limit' => 10])
            ->andReturn($response);

        $pager = $this->messageService->list('83facc8c-11c5-11ef-8e7a-00155d34f1bf', 1, 10);
        $this->assertInstanceOf(Message::class, $pager->items()->first());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
