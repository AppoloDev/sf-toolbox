<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern;

interface TimestampableInterface
{
    public function getCreatedAt(): ?\DateTimeInterface;

    public function setCreatedAt(?\DateTimeInterface $createdAt): self;

    public function getUpdatedAt(): ?\DateTimeInterface;

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self;
}
