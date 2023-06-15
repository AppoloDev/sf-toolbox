<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

interface LocalisableInterface
{
    public function getAddress(): ?string;

    public function setAddress(?string $address): self;

    public function getZipCode(): ?string;

    public function setZipCode(?string $zipCode): self;

    public function getCity(): ?string;

    public function setCity(?string $city): self;
}
