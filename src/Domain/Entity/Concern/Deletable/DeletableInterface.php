<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Deletable;

interface DeletableInterface
{
    public function setDeleted(bool $deleted): self;

    public function isDeleted(): bool;
}
