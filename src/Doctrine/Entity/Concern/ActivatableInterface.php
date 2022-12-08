<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

interface ActivatableInterface
{
    public function setEnabled(bool $enabled): self;

    public function isEnabled(): bool;
}
