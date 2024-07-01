<?php

namespace Unswer;

use Unswer\Services\ContactService;
use Unswer\Services\MessageService;

class Client extends BaseClient
{
    private $contactService;
    private $messageService;

    public function contacts()
    {
        $this->contactService ??= new ContactService();
        return $this->contactService;
    }

    public function messages()
    {
        $this->messageService ??= new MessageService();
        return $this->messageService;
    }
}
