<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Blockable;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Blockable
{
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['block'])]
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
