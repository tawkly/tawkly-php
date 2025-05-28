<?php

namespace Tawkly\Models;

use stdClass;

class Room
{
    private string $id;
    private int $phone;
    private string $tag;
    private bool $isBlocked;
    private Message $lastest;

    public function __construct(stdClass $room)
    {
        $this->id = $room->id;
        $this->phone = intval($room->phone);
        $this->tag = $room->tag;
        $this->isBlocked = boolval($room->is_blocked);
        $this->lastest = new Message($room->lastest);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function getLastest(): Message
    {
        return $this->lastest;
    }
}
