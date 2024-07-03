<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Unswer\Services\ContactService;
use Unswer\Http;
use Mockery;
use ReflectionClass;
use Unswer\Exceptions\UnswerException;
use Unswer\Models\Contact;

class ContactServiceTest extends TestCase
{
    /**
     * @var ContactService
     */
    protected $contactService;

    /**
     * @var \Mockery\LegacyMockInterface&\Mockery\MockInterface&object
     */
    protected $httpMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpMock = Mockery::mock(Http::class);
        $this->contactService = new ContactService('test_api_key', 'test_app_id');

        $reflection = new ReflectionClass(ContactService::class);
        $httpProperty = $reflection->getProperty('http');
        $httpProperty->setAccessible(true);
        $httpProperty->setValue($this->contactService, $this->httpMock);
    }

    public function testCreateContactWithInvalidParams()
    {
        $this->expectException(UnswerException::class);
        $this->contactService->create([['name' => 'John Doe', 'phone' => 'unknown']]);
    }

    public function testCreateContactSuccess()
    {
        $request = [['name' => 'John Doe', 'phone' => 6283812345678]];

        $this->httpMock->shouldReceive('post')
            ->once()
            ->with('contacts/test_app_id', $request)
            ->andReturn((object) ['statusCode' => 201]);

        $created = $this->contactService->create($request);
        $this->assertTrue($created);
    }

    public function testRetrieveContactListsWithInvalidParams()
    {
        $this->expectException(UnswerException::class);
        $this->contactService->all(1, 80);
    }

    public function testRetrieveContactListsSuccess()
    {
        $response = (object) [
            'data' => [
                (object) [
                    'id' => 'a7af8912-abd8-4de2-b0e4-731a002d967d',
                    'phone' => 6283812345678,
                    'tag' => 'John Doe',
                    'is_blocked' => false,
                    'created_at' => '2024-07-01T15:31:06.000+07:00',
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
            ->with('contacts/test_app_id', ['page' => 1, 'limit' => 10])
            ->andReturn($response);

        $pager = $this->contactService->all(1, 10);
        $this->assertInstanceOf(Contact::class, $pager->items()->first());
    }

    public function testGetContactDetailThrowException()
    {
        $this->expectException(UnswerException::class);

        $this->httpMock->shouldReceive('get')
            ->once()
            ->with('contacts/test_app_id/1')
            ->andThrow(new UnswerException());
        $this->contactService->get('1');
    }

    public function testGetContactDetailSuccess()
    {
        $response = (object) [
            'data' => (object) [
                "id" => "6ad2d05f-11c5-11ef-8e7a-00155d34f1bf",
                "phone" => 6283812345678,
                "tags" => ["John Doe"],
                "is_blocked" => false,
                "created_at" => "2024-05-14T14:58:44.000+07:00"
            ],
        ];
        $contactId = '6ad2d05f-11c5-11ef-8e7a-00155d34f1bf';

        $this->httpMock->shouldReceive('get')
            ->once()
            ->with('contacts/test_app_id/' . $contactId)
            ->andReturn($response);

        $contact = $this->contactService->get($contactId);
        $this->assertInstanceOf(Contact::class, $contact);
    }

    public function testBlockContactThrowException()
    {
        $this->expectException(UnswerException::class);

        $this->httpMock->shouldReceive('put')
            ->once()
            ->with('contacts/test_app_id/1')
            ->andThrow(new UnswerException());
        $this->contactService->block('1');
    }

    public function testBlockContactSuccess()
    {
        $response = (object) [
            'data' => (object) [
                "id" => "6ad2d05f-11c5-11ef-8e7a-00155d34f1bf",
                "phone" => 6283812345678,
                "tag" => "John Doe",
                "is_blocked" => true,
                "created_at" => "2024-05-14T14:58:44.000+07:00"
            ],
        ];
        $contactId = '6ad2d05f-11c5-11ef-8e7a-00155d34f1bf';

        $this->httpMock->shouldReceive('put')
            ->once()
            ->with('contacts/test_app_id/' . $contactId)
            ->andReturn($response);

        $contact = $this->contactService->block($contactId);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertTrue($contact->isBlocked());
    }

    /**
     * @depends testBlockContactThrowException
     */
    public function testUnblockContactThrowException()
    {
        $this->expectException(UnswerException::class);

        $this->httpMock->shouldReceive('put')
            ->once()
            ->with('contacts/test_app_id/1')
            ->andThrow(new UnswerException());
        $this->contactService->block('1');
    }

    /**
     * @depends testBlockContactSuccess
     */
    public function testUnblockContactSuccess()
    {
        $response = (object) [
            'data' => (object) [
                "id" => "6ad2d05f-11c5-11ef-8e7a-00155d34f1bf",
                "phone" => 6283812345678,
                "tag" => "John Doe",
                "is_blocked" => false,
                "created_at" => "2024-05-14T14:58:44.000+07:00"
            ],
        ];
        $contactId = '6ad2d05f-11c5-11ef-8e7a-00155d34f1bf';

        $this->httpMock->shouldReceive('put')
            ->once()
            ->with('contacts/test_app_id/' . $contactId)
            ->andReturn($response);

        $contact = $this->contactService->block($contactId);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertFalse($contact->isBlocked());
    }

    public function testDeleteContactThrowException()
    {
        $this->expectException(UnswerException::class);

        $this->httpMock->shouldReceive('delete')
            ->once()
            ->with('contacts/test_app_id/1')
            ->andThrow(new UnswerException());
        $this->contactService->delete('1');
    }

    public function testDeleteContactSuccess()
    {
        $contactId = '6ad2d05f-11c5-11ef-8e7a-00155d34f1bf';

        $this->httpMock->shouldReceive('delete')
            ->once()
            ->with('contacts/test_app_id/' . $contactId)
            ->andReturn((object) ['statusCode' => 200]);

        $deleted = $this->contactService->delete($contactId);
        $this->assertTrue($deleted);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
