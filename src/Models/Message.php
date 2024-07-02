<?php

namespace Unswer\Models;

class Message
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $body;

    /**
     * @var string
     */
    private $attachmentUrl;

    /**
     * @var string
     */
    private $status;

    /**
     * @var bool
     */
    private $isMe;

    /**
     * @var string
     */
    private $receivedAt;

    /**
     * @param mixed $message
     */
    public function __construct($message)
    {
        $this->id = $message->id;
        $this->type = $message->type;
        $this->body = (object) $message->{$message->type};
        $this->attachmentUrl = $message->attachment;
        $this->status = $message->status;
        $this->isMe = boolval($message->is_me);
        $this->receivedAt = $message->received_at;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getAttachmentUrl()
    {
        return $this->attachmentUrl;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isMe()
    {
        return $this->isMe;
    }

    /**
     * @return string ISO-8601
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }
}
