<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Publishable;

interface PublishableInterface
{
    public function getPublicationStartDate(): ?\DateTimeInterface;

    public function setPublicationStartDate(?\DateTimeInterface $publicationStartDate): self;

    public function getPublicationEndDate(): ?\DateTimeInterface;

    public function setPublicationEndDate(?\DateTimeInterface $publicationStartDate): self;
}
