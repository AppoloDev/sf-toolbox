<?php

namespace AppoloDev\SFToolboxBundle\Message;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\Address;

class EmailMessage
{
    /** @var Address[] */
    protected array $recipients = [];

    public function __construct(
        array $recipients,
        private readonly string $object,
        private readonly string $template,
        private readonly array $parameters = [],
        private readonly string $locale = 'en',
        private readonly ?File $file = null,
        private readonly string $filename = 'attachment',
    ) {
        $this->recipients = array_map(fn ($recipient): Address => new Address($recipient), $recipients);
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getObject(): string
    {
        return $this->object;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
