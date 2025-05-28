<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use Tawkly\Models\Template;

class TemplateTest extends TestCase
{
    private Template $template;

    public function setUp(): void
    {
        parent::setUp();

        $data = (object) [
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
        ];

        $this->template = new Template($data);
    }

    public function testCanGetId()
    {
        $this->assertEquals('1817274575434611', $this->template->getId());
    }

    public function testCanGetName()
    {
        $this->assertEquals('greeting_message', $this->template->getName());
    }

    public function testCanGetLanguage()
    {
        $this->assertEquals('en_US', $this->template->getLanguage());
    }

    public function testCanGetCategory()
    {
        $this->assertEquals('UTILITY', $this->template->getCategory());
    }

    public function testCanGetStatus()
    {
        $this->assertEquals('APPROVED', $this->template->getStatus());
    }

    public function testCanGetComponents()
    {
        $components = [
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
        ];

        $this->assertEquals($components, $this->template->getComponents());
    }

    public function testCanGetExtras()
    {
        $extras = (object) [
            'cta_url_link_tracking_opted_out' => false,
            'quality_score' => [
                'score' => 'UNKNOWN',
                'date' => 1720056401,
            ],
            'rejected_reason' => 'NONE',
        ];

        $this->assertEquals($extras, $this->template->getExtras());
    }
}
