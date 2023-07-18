<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Sluggable;

interface SluggableInterface
{
    public function getSlug(): ?string;

    public function setSlug(string $slug): self;
}
