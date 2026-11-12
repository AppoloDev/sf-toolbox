<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Geolocalizable;

interface GeolocalizableInterface
{
    public function getAddress(): ?string;

    public function setAddress(?string $address): self;

    public function getZipCode(): ?string;

    public function setZipCode(?string $zipCode): self;

    public function getCity(): ?string;

    public function setCity(?string $city): self;

    public function getLat(): ?float;

    public function setLat(?float $lat): self;

    public function getLng(): ?float;

    public function setLng(?float $lng): self;

    public function getFormattedAddress(): ?string;

    public function setFormattedAddress(?string $formattedAddress): self;
}
