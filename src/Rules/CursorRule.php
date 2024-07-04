<?php

namespace Unswer\Rules;

use Rakit\Validation\Rule;

class CursorRule extends Rule
{
    protected $message = ':attribute is not valid. If "after" is filled in, "before" must be empty, and vice versa.';

    public function check($value): bool
    {
        $before = $this->getAttribute()->getValue('before');
        $after = $this->getAttribute()->getValue('after');

        if (!empty($after) && !empty($before)) {
            return false;
        }

        return true;
    }
}
