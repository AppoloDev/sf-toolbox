<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Publishable
{
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['publish'])]
    private bool $enabled = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['publish'])]
    private ?DateTimeInterface $publicationStartDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['publish'])]
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
