<?php

namespace Unswer\Models;

use Illuminate\Support\Collection;
use stdClass;

class Contact
{
    private string $id;
    private int $phone;
    private Collection $tags;
    private string $tag;
    private bool $isBlocked;
    private string $createdAt;

    public function __construct(stdClass $contact)
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @return string ISO-8601
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
