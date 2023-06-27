<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern;

interface ActivatableInterface
{
    public function setEnabled(bool $enabled): self;

    public function isEnabled(): bool;
}
