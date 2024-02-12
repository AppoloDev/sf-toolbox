<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Archivable;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Archivable
{
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Groups(['archive'])]
    private bool $archived = false;

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }
}
