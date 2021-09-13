<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

interface ActivatableInterface
{
    public function setEnable(bool $enable): self;

    public function isEnabled(): bool;
}
