<?php

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Unswer\Client;
use Unswer\Exceptions\UnswerException;
use Unswer\Models\Contact;
use Unswer\Services\ContactService;

final class ContactTest extends TestCase
{
    private ContactService $service;

    private static $contact;

    public function __construct()
    {
        parent::__construct();

        $client = new Client(null, null, [
            'host' => 'http://localhost:8081/api',
        ]);
        $this->service = $client->contacts();
    }

    public function testCanCreate()
    {
        $created = $this->service->create([
            ['name' => 'John Doe', 'phone' => 6283812345678],
            ['name' => 'Jane Doe', 'phone' => 6285112345678],
        ]);

        $this->assertTrue($created);
    }

    /**
     * @depends testCanCreate
     */
    public function testCanGetLists()
    {
        $contacts = $this->service->all(1, 10);
        $data = array_filter($contacts->toArray(), function ($contact) {
            return $contact->getPhone() === 6283812345678;
        });
        self::$contact = reset($data);

        $this->assertInstanceOf(Collection::class, $contacts);
        $this->assertInstanceOf(Contact::class, self::$contact);
    }

    /**
     * @depends testCanGetLists
     */
    public function testCanGetDetail()
    {
        $this->assertNotNull(
            self::$contact,
            'Contact is null, make sure testCanGetLists runs and sets the contact'
        );

        $contact = $this->service->get(self::$contact->getId());

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals(self::$contact, $contact);
        $this->assertEquals(Collection::wrap('John Doe'), $contact->getTags());
        $this->assertEquals('John Doe', $contact->getTag());
        $this->assertIsString($contact->getCreatedAt());
    }

    /**
     * @depends testCanGetDetail
     */
    public function testCanBlock()
    {
        $contact = $this->service->block(self::$contact->getId());

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertTrue($contact->isBlocked());
    }
    /**
     * @depends testCanBlock
     */
    public function testCanUnblock()
    {
        $contact = $this->service->block(self::$contact->getId());

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertFalse($contact->isBlocked());
    }

    /**
     * @depends testCanUnblock
     */
    public function testCanDelete()
    {
        $deleted = $this->service->delete(self::$contact->getId());
        $this->assertTrue($deleted);
    }

    public function testWithErrorValidation()
    {
        $this->expectException(UnswerException::class);
        $this->service->create(['name' => 58, 'phone' => 'unknown']);
    }

    public function testWithInvalidLimit()
    {
        $this->expectException(UnswerException::class);
        $this->service->all(['limit' => 80]);
    }
}
