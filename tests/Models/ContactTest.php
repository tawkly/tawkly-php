<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use Unswer\Models\Contact;
use Illuminate\Support\Collection;

class ContactTest extends TestCase
{
    private Contact $contact;

    public function setUp(): void
    {
        parent::setUp();

        $data = (object) [
            'id' => '6ad2d05f-11c5-11ef-8e7a-00155d34f1bf',
            'phone' => 6283812345678,
            'tags' => ['John Doe', 'John'],
            'is_blocked' => false,
            'created_at' => '2024-05-14T14:58:44.000+07:00'
        ];

        $this->contact = new Contact($data);
    }

    public function testCanGetId()
    {
        $this->assertEquals('6ad2d05f-11c5-11ef-8e7a-00155d34f1bf', $this->contact->getId());
    }

    public function testCanGetPhone()
    {
        $this->assertEquals(6283812345678, $this->contact->getPhone());
    }

    public function testCanGetTags()
    {
        $this->assertInstanceOf(Collection::class, $this->contact->getTags());
        $this->assertEquals(['John Doe', 'John'], $this->contact->getTags()->toArray());
    }

    public function testCanGetTag()
    {
        $this->assertEquals('John Doe', $this->contact->getTag());
    }

    public function testCanGetBlockedStatus()
    {
        $this->assertFalse($this->contact->isBlocked());
    }

    public function testCanGetCreatedAt()
    {
        $this->assertEquals('2024-05-14T14:58:44.000+07:00', $this->contact->getCreatedAt());
    }
}
