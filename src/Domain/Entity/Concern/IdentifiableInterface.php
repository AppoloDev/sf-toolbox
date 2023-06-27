<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern;

use Symfony\Component\Uid\Uuid;

interface IdentifiableInterface
{
    public function getId(): ?Uuid;
}
