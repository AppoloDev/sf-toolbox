<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

interface SluggableInterface
{
    public function getSlug(): ?string;

    public function setSlug(string $slug): self;
}
