<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

interface DeletableInterface
{
    public function setDeleted(bool $deleted): self;

    public function isDeleted(): bool;
}
