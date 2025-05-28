<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Tawkly\Services\TemplateService;
use Tawkly\Http;
use Mockery;
use ReflectionClass;
use Tawkly\Exceptions\TawklyException;
use Tawkly\Models\Template;

class TemplateServiceTest extends TestCase
{
    /**
     * @var \Mockery\LegacyMockInterface&\Mockery\MockInterface&object
     */
    protected $httpMock;
    protected TemplateService $templateService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpMock = Mockery::mock(Http::class);
        $this->templateService = new TemplateService('test_api_key', 'test_app_id');

        $reflection = new ReflectionClass(TemplateService::class);
        $httpProperty = $reflection->getProperty('http');
        $httpProperty->setAccessible(true);
        $httpProperty->setValue($this->templateService, $this->httpMock);
    }

    public function testRetrieveTemplateListsWithInvalidParams()
    {
        $this->expectException(TawklyException::class);
        $this->templateService->all(null, null, 80);
    }

    public function testRetrieveTemplateListsSuccess()
    {
        $response = (object) [
            'data' => [
                (object) [
                    'id' => '578193950843926',
                    'name' => 'sample_movie_ticket_confirmation',
                    'language' => 'es',
                    'category' => 'UTILITY',
                    'status' => 'APPROVED',
                ],
            ],
            'meta' => (object) [
                'cursor' => (object) [
                    'before' => 'MAZDZD',
                    'after' => 'OQZDZD',
                ],
            ]
        ];

        $this->httpMock->shouldReceive('get')
            ->once()
            ->with('templates/test_app_id', ['before' => null, 'after' => null, 'limit' => 10])
            ->andReturn($response);

        $pager = $this->templateService->all();
        $this->assertInstanceOf(Template::class, $pager->items()->first());
    }

    public function testGetTemplateDetailThrowException()
    {
        $this->expectException(TawklyException::class);

        $this->httpMock->shouldReceive('get')
            ->once()
            ->with('templates/test_app_id/1')
            ->andThrow(new TawklyException());
        $this->templateService->get('1');
    }

    public function testGetTemplateDetailSuccess()
    {
        $response = (object) [
            'data' => (object) [
                'id' => '1817274575434611',
                'name' => 'greeting_message',
                'language' => 'en_US',
                'category' => 'UTILITY',
                'status' => 'APPROVED',
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => 'Welcome to {{1}}!\n\nLorem ipsum dolor sit amet, your name is {{2}}.',
                        'example' => [
                            'body_text' => [
                                0 => [
                                    0 => 'Tawkly',
                                    1 => 'Ahmed',
                                ],
                            ],
                        ],
                    ],
                ],
                '$extras' => [
                    'cta_url_link_tracking_opted_out' => false,
                    'quality_score' => [
                        'score' => 'UNKNOWN',
                        'date' => 1720056401,
                    ],
                    'rejected_reason' => 'NONE',
                ],
            ],
        ];
        $templateId = '1817274575434611';

        $this->httpMock->shouldReceive('get')
            ->once()
            ->with('templates/test_app_id/' . $templateId)
            ->andReturn($response);

        $template = $this->templateService->get($templateId);
        $this->assertInstanceOf(Template::class, $template);
    }

    public function testDeleteTemplateThrowException()
    {
        $this->expectException(TawklyException::class);

        $this->httpMock->shouldReceive('delete')
            ->once()
            ->with('templates/test_app_id/1')
            ->andThrow(new TawklyException());
        $this->templateService->delete('1');
    }

    public function testDeleteTemplateSuccess()
    {
        $templateId = '1817274575434611';

        $this->httpMock->shouldReceive('delete')
            ->once()
            ->with('templates/test_app_id/' . $templateId)
            ->andReturn((object) ['statusCode' => 200]);

        $deleted = $this->templateService->delete($templateId);
        $this->assertTrue($deleted);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
