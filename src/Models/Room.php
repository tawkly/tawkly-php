<?php

namespace Unswer\Models;

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
     * @var string
     */
    private $tag;

    /**
     * @var bool
     */
    private $isBlocked;

    /**
     * @var Message
     */
    private $lastest;

    /**
     * @param mixed $room
     */
    public function __construct($room)
    {
        $this->id = $room->id;
        $this->phone = intval($room->phone);
        $this->tag = $room->tag;
        $this->isBlocked = boolval($room->is_blocked);
        $this->lastest = new Message($room->lastest);
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
     * @return Message
     */
    public function getLastest()
    {
        return $this->lastest;
    }
}
