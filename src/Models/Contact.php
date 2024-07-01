<?php

namespace Unswer\Models;

use Illuminate\Support\Collection;

class Contact
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
     * @var string
     */
    private $createdAt;

    public function __construct($id, $phone, $tags, $isBlocked, $createdAt)
    {
        $this->id = $id;
        $this->phone = $phone;
        $this->tags = new Collection($tags);
        $this->isBlocked = $isBlocked;
        $this->createdAt = $createdAt;
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
     * @return string ISO-8601
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
