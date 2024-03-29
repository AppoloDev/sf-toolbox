<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Deletable;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Deletable
{
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Groups(['delete'])]
    private bool $deleted = false;

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
