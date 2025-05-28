<?php

namespace Tawkly;

use Tawkly\Services\ContactService;
use Tawkly\Services\MessageService;
use Tawkly\Services\TemplateService;

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
