<?php

namespace App\Domain\Shared\Concern;

interface DeletableInterface
{
    public function setDeleted(bool $deleted): self;

    public function isDeleted(): bool;
}
