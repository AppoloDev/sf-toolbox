<?php

namespace AppoloDev\SFToolboxBundle\Message;

use Symfony\Component\Mime\Address;

class EmailMessage
{
    /** @var Address[] */
    protected array $recipients = [];

    public function __construct(
        array $recipients,
        private readonly string $object,
        private readonly string $template,
        private readonly array $parameters = []
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
}
