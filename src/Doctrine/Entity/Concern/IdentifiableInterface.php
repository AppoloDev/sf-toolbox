<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

use Symfony\Component\Uid\UuidInterface;

interface IdentifiableInterface
{
    public function getId(): ?UuidInterface;
}
