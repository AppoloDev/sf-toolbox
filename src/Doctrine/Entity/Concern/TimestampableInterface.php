<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

use DateTimeInterface;

interface TimestampableInterface
{
    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(?DateTimeInterface $createdAt): self;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self;
}
