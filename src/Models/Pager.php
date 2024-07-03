<?php

namespace Unswer\Models;

use Illuminate\Support\Collection;
use stdClass;

class Pager
{
    private array $collection;
    private stdClass $meta;

    /**
     * @var callable
     */
    private $method;

    /**
     * @param callable $method
     */
    public function __construct(array $collection, stdClass $meta, $method)
    {
        $this->collection = $collection;
        $this->meta = $meta;
        $this->method = $method;
    }

    public function next(): ?Pager
    {
        if (property_exists($this->meta, 'cursors')) {
            return call_user_func($this->method, $this->meta->cursors->after);
        }

        if ($this->meta->current_page === $this->meta->last_page) {
            return null;
        }

        return call_user_func($this->method, $this->meta->next_page);
    }

    public function previous(): ?Pager
    {
        if (property_exists($this->meta, 'cursors')) {
            return call_user_func($this->method, $this->meta->cursors->before);
        }

        if ($this->meta->current_page === $this->meta->first_page) {
            return null;
        }

        return call_user_func($this->method, $this->meta->previous_page);
    }

    public function items(): Collection
    {
        return new Collection($this->collection);
    }
}
