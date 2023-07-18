<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Activable;

interface ActivatableInterface
{
    public function setEnabled(bool $enabled): self;

    public function isEnabled(): bool;
}
