<?php

use PHPUnit\Framework\TestCase;
use Rakit\Validation\Validator;
use Unswer\Rules\CursorRule;

class CursorRuleTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
        $this->validator->addValidator('cursor', new CursorRule());
    }

    public function testValidCursor()
    {
        $validation = $this->validator->validate(
            ['after' => 'some_value'],
            ['before' => 'cursor', 'after' => 'cursor'],
        );

        $this->assertTrue($validation->passes());
    }

    public function testInvalidCursor()
    {
        $validation = $this->validator->validate(
            ['before' => 'some_value', 'after' => 'some_value'],
            ['before' => 'cursor', 'after' => 'cursor'],
        );

        $this->assertFalse($validation->passes());
        $this->assertTrue($validation->errors()->has('after'));
    }
}
