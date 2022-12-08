<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

use Symfony\Component\Uid\Uuid;

interface IdentifiableInterface
{
    public function getId(): ?Uuid;
}
