<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Localisable
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
}
