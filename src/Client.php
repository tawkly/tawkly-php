<?php

namespace Unswer;

use Unswer\Services\ContactService;
use Unswer\Services\MessageService;
use Unswer\Services\TemplateService;

class Client extends BaseClient
{
    private $contactService;
    private $templateService;
    private $messageService;

    public function contacts(): ContactService
    {
        $this->contactService ??= new ContactService();
        return $this->contactService;
    }

    public function templates(): TemplateService
    {
        $this->templateService ??= new TemplateService();
        return $this->templateService;
    }

    public function messages(): MessageService
    {
        $this->messageService ??= new MessageService();
        return $this->messageService;
    }
}
