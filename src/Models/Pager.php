<?php

namespace Unswer\Models;

use Illuminate\Support\Collection;

class Pager
{
    /**
     * @var mixed
     */
    private $collection;

    /**
     * @var mixed
     */
    private $meta;

    /**
     * @var callable
     */
    private $method;

    /**
     * @param mixed $collection
     * @param mixed $meta
     * @param callable $method
     */
    public function __construct($collection, $meta, $method)
    {
        $this->collection = $collection;
        $this->meta = $meta;
        $this->method = $method;
    }

    /**
     * @return Pager
     */
    public function next()
    {
        if (property_exists($this->meta, 'cursors')) {
            return call_user_func($this->method, $this->meta->cursors->after);
        }

        if ($this->meta->current_page === $this->meta->last_page) {
            return null;
        }

        return call_user_func($this->method, $this->meta->next_page);
    }

    /**
     * @return Pager
     */
    public function previous()
    {
        if (property_exists($this->meta, 'cursors')) {
            return call_user_func($this->method, $this->meta->cursors->before);
        }

        if ($this->meta->current_page === $this->meta->first_page) {
            return null;
        }

        return call_user_func($this->method, $this->meta->previous_page);
    }

    /**
     * @return Collection
     */
    public function items()
    {
        return new Collection($this->collection);
    }
}
