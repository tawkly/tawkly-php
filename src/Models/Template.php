<?php

namespace Tawkly\Models;

use stdClass;

class Template
{
    private ?string $id;
    private string $name;
    private string $language;
    private string $category;
    private string $status;
    private ?array $components;
    private ?stdClass $extras;

    public function __construct(stdClass $template)
    {
        $this->id = $template->id ?? null;
        $this->name = $template->name;
        $this->language = $template->language;
        $this->category = $template->category;
        $this->status = $template->status;
        $this->components = $template->components ?? null;
        $this->extras = property_exists($template, '$extras') ? (object) $template->{'$extras'} : null;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getComponents(): ?array
    {
        return $this->components;
    }

    public function getExtras(): ?stdClass
    {
        return $this->extras;
    }
}
