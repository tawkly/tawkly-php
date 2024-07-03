<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use Unswer\Models\Message;

class MessageTest extends TestCase
{
    /**
     * @var Message
     */
    private $message;

    public function setUp(): void
    {
        parent::setUp();

        $data = (object) [
            'id' => 'c8009895-76bf-4bc1-a7c5-3d55b95aa577',
            'type' => 'text',
            'text' => [
                'body' => 'lorem ipsum dolor sit amet'
            ],
            'attachment' => null,
            'status' => 'sent',
            'is_me' => false,
            'received_at' => '2024-07-02T15:09:23.000+07:00'
        ];

        $this->message = new Message($data);
    }

    public function testCanGetId()
    {
        $this->assertEquals('c8009895-76bf-4bc1-a7c5-3d55b95aa577', $this->message->getId());
    }

    public function testCanGetType()
    {
        $this->assertEquals('text', $this->message->getType());
    }

    public function testCanGetBody()
    {
        $this->assertEquals(
            (object) ['body' => 'lorem ipsum dolor sit amet'],
            $this->message->getBody()
        );
    }

    public function testCanGetAttachmentUrl()
    {
        $this->assertNull($this->message->getAttachmentUrl());
    }

    public function testCanGetStatus()
    {
        $this->assertEquals('sent', $this->message->getStatus());
    }

    public function testCanGetSenderStatus()
    {
        $this->assertFalse($this->message->isMe());
    }

    public function testCanGetReceivedAt()
    {
        $this->assertEquals('2024-07-02T15:09:23.000+07:00', $this->message->getReceivedAt());
    }
}
