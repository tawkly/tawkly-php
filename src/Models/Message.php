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

    public function __construct($id, $type, $body, $attachmentUrl, $status, $isMe, $receivedAt)
    {
        $this->id = $id;
        $this->type = $type;
        $this->body = $body;
        $this->attachmentUrl = $attachmentUrl;
        $this->status = $status;
        $this->isMe = $isMe;
        $this->receivedAt = $receivedAt;
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
        // convert array to object
        return json_decode(json_encode($this->body));
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
