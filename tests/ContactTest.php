<?php

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Unswer\Client;
use Unswer\Models\Contact;
use Unswer\Services\ContactService;

final class ContactTest extends TestCase
{
    private ContactService $service;
    private static $contact;

    public function __construct()
    {
        parent::__construct();

        $client = new Client(null, null, 'http://localhost:8081/api');
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
        self::$contact = $contacts->first();

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
            "Contact is null, make sure testCanGetLists runs and sets the contact"
        );
        $contact = $this->service->get(self::$contact->getId());

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals(self::$contact, $contact);
    }

    /**
     * @depends testCanGetLists
     */
    public function testCanBlock()
    {
        $this->assertNotNull(
            self::$contact,
            "Contact is null, make sure testCanGetLists runs and sets the contact"
        );
        $contact = $this->service->block(self::$contact->getId());

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertTrue($contact->isBlocked());
    }
    /**
     * @depends testCanBlock
     */
    public function testCanUnblock()
    {
        $this->assertNotNull(
            self::$contact,
            "Contact is null, make sure testCanGetLists runs and sets the contact"
        );
        $contact = $this->service->block(self::$contact->getId());

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertFalse($contact->isBlocked());
    }

    /**
     * @depends testCanGetLists
     */
    public function testCanDelete()
    {
        $this->assertNotNull(
            self::$contact,
            "Contact is null, make sure testCanGetLists runs and sets the contact"
        );

        $deleted = $this->service->delete(self::$contact->getId());
        $this->assertTrue($deleted);
    }
}
