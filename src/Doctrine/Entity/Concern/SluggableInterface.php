<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

interface SluggableInterface
{
    public function getSlug(): ?string;

    public function setSlug(string $slug): self;
}
