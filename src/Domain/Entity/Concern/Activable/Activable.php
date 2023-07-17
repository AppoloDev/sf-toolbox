<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Activable;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Activable
{
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['active'])]
    private bool $enabled = true;

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
