<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait Blockable
{
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $blocked = false;

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }
}
