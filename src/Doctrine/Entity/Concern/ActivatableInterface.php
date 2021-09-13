<?php

namespace App\Domain\Shared\Concern;

interface ActivatableInterface
{
    public function setEnable(bool $enable): self;

    public function isEnabled(): bool;
}
