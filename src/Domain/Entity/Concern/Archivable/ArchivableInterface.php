<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Archivable;

interface ArchivableInterface
{
    public function setArchived(bool $archived): self;

    public function isArchived(): bool;
}
