<?php

namespace App\Domain\Shared\Concern;

interface BlockableInterface
{
    public function setBlocked(bool $blocked): self;

    public function isBlocked(): bool;
}
