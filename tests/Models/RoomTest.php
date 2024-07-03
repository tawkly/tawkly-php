<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use Unswer\Models\Room;
use Unswer\Models\Message;

class RoomTest extends TestCase
{
    /**
     * @var Room
     */
    private $room;

    public function setUp(): void
    {
        parent::setUp();

        $data = (object) [
            'id' => '83facc8c-11c5-11ef-8e7a-00155d34f1bf',
            'phone' => 6283812345678,
            'tag' => 'John Doe',
            'is_blocked' => false,
            'lastest' => (object) [
                'id' => 'c8009895-76bf-4bc1-a7c5-3d55b95aa577',
                'type' => 'text',
                'text' => [
                    'body' => 'lorem ipsum dolor sit amet'
                ],
                'attachment' => null,
                'status' => 'sent',
                'is_me' => false,
                'received_at' => '2024-07-02T15:09:23.000+07:00',
            ],
        ];

        $this->room = new Room($data);
    }

    public function testCanGetId()
    {
        $this->assertEquals('83facc8c-11c5-11ef-8e7a-00155d34f1bf', $this->room->getId());
    }

    public function testCanGetPhone()
    {
        $this->assertEquals(6283812345678, $this->room->getPhone());
    }

    public function testCanGetTag()
    {
        $this->assertEquals('John Doe', $this->room->getTag());
    }

    public function testCanGetBlockedStatus()
    {
        $this->assertFalse($this->room->isBlocked());
    }

    public function testCanGetLastestMessage()
    {
        $this->assertInstanceOf(Message::class, $this->room->getLastest());
    }
}
