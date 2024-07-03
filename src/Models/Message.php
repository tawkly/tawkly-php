<?php

namespace Unswer\Models;

use stdClass;

class Message
{
    private string $id;
    private string $type;
    private stdClass $body;
    private ?string $attachmentUrl;
    private string $status;
    private bool $isMe;
    private string $receivedAt;

    public function __construct(stdClass $message)
    {
        $this->id = $message->id;
        $this->type = $message->type;
        $this->body = (object) $message->{$message->type};
        $this->attachmentUrl = $message->attachment;
        $this->status = $message->status;
        $this->isMe = boolval($message->is_me);
        $this->receivedAt = $message->received_at;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBody(): stdClass
    {
        return $this->body;
    }

    public function getAttachmentUrl(): ?string
    {
        return $this->attachmentUrl;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isMe(): bool
    {
        return $this->isMe;
    }

    /**
     * @return string ISO-8601
     */
    public function getReceivedAt(): string
    {
        return $this->receivedAt;
    }
}
