<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Deletable
{
    #[ORM\Column(type: Types::BOOLEAN)]
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
