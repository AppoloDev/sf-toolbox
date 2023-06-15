<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

interface DeletableInterface
{
    public function setDeleted(bool $deleted): self;

    public function isDeleted(): bool;
}
