<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

interface BlockableInterface
{
    public function setBlocked(bool $blocked): self;

    public function isBlocked(): bool;
}
