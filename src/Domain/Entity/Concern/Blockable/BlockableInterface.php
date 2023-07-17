<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Blockable;

interface BlockableInterface
{
    public function setBlocked(bool $blocked): self;

    public function isBlocked(): bool;
}
