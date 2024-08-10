<?php

namespace Unswer\Rules;

use Rakit\Validation\Rule;

class EmptyIfRule extends Rule
{
    protected $message = ":attribute must be empty if :other is :value";

    public function check($value): bool
    {
        $category = $this->getAttribute()->getValue('category');
        return !($category === 'UTILITY' || $category === 'MARKETING') || is_null($value);
    }
}
