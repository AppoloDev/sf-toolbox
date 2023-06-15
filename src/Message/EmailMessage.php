<?php

namespace AppoloDev\SFToolboxBundle\Message;

use Symfony\Component\Mime\RawMessage;

class EmailMessage extends RawMessage
{
    protected array $emailData = [];
    public function __construct(array $recipients, string $object, string $template, array $parameters = [])
    {
        $this->emailData = [
            'recipients' => $recipients,
            'object' => $object,
            'template' => $template,
            'parameters' => $parameters
        ];
        parent::__construct($this->emailData);
    }

    public function __serialize(): array
    {
        return $this->emailData;
    }

    public function __unserialize(array $data): void
    {
        $this->emailData = $data;
    }
}