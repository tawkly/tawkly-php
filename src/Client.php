<?php

namespace Unswer;

use Unswer\Services\ContactService;
use Unswer\Services\MessageService;

class Client extends BaseClient
{
    private $contactService;
    private $messageService;

    /**
     * @return ContactService
     */
    public function contacts()
    {
        $this->contactService ??= new ContactService();
        return $this->contactService;
    }

    /**
     * @return MessageService
     */
    public function messages()
    {
        $this->messageService ??= new MessageService();
        return $this->messageService;
    }
}
