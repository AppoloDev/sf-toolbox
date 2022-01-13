<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait Publishable
{
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $enabled = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $publicationStartDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $publicationEndDate = null;

    public function getPublicationStartDate(): ?DateTimeInterface
    {
        return $this->publicationStartDate;
    }

    public function setPublicationStartDate(?DateTimeInterface $publicationStartDate): self
    {
        $this->publicationStartDate = $publicationStartDate;

        return $this;
    }

    public function getPublicationEndDate(): ?DateTimeInterface
    {
        return $this->publicationEndDate;
    }

    public function setPublicationEndDate(?DateTimeInterface $publicationEndDate): self
    {
        $this->publicationEndDate = $publicationEndDate;

        return $this;
    }
}
