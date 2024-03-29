<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Geolocalizable;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Geolocalizable
{
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['localisation'])]
    private ?string $address;

    #[ORM\Column(type: Types::STRING, length: 5, nullable: true)]
    #[Groups(['localisation'])]
    private ?string $zipCode;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['localisation'])]
    private ?string $city;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['localisation'])]
    private ?float $lat;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['localisation'])]
    private ?float $lng;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['localisation'])]
    private ?string $formattedAddress;

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;
        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;
        return $this;
    }

    public function getFormattedAddress(): ?string
    {
        return $this->formattedAddress;
    }

    public function setFormattedAddress(?string $formattedAddress): self
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }
}
