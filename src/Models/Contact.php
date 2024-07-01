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
     * @var string
     */
    private $tag;

    /**
     * @var bool
     */
    private $isBlocked;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @param mixed $contact
     */
    public function __construct($contact)
    {
        $tags = property_exists($contact, 'tags')
            ? $contact->tags
            : (property_exists($contact, 'tag') ? [$contact->tag] : []);

        $this->id = $contact->id;
        $this->phone = intval($contact->phone);
        $this->tags = new Collection($tags);
        $this->tag = $this->tags->first();
        $this->isBlocked = boolval($contact->is_blocked);
        $this->createdAt = $contact->created_at;
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
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
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
