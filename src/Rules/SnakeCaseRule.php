<?php

namespace Unswer\Rules;

use Rakit\Validation\Rule;

class SnakeCaseRule extends Rule
{
    protected $message = ":attribute must be in snake_case format";

    public function check($value): bool
    {
        return (bool) preg_match('/^[a-z0-9]+(_[a-z0-9]+)*$/', $value);
    }
}
