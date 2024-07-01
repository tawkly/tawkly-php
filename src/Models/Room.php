<?php

namespace Unswer\Models;

use Illuminate\Support\Collection;

class Room
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $phone;

    /**
     * @var Collection
     */
    private $tags;

    /**
     * @var bool
     */
    private $isBlocked;

    /**
     * @var Message
     */
    private $latest;

    public function __construct($id, $phone, $tags, $isBlocked, $latest)
    {
        $this->id = $id;
        $this->phone = $phone;
        $this->tags = new Collection($tags);
        $this->isBlocked = $isBlocked;
        $this->latest = $latest;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * @return Message
     */
    public function latest()
    {
        return $this->latest;
    }
}
