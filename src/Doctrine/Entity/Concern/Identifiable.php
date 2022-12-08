<?php

namespace AppoloDev\SFToolbox\Doctrine\Entity\Concern;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidInterface;

trait Identifiable
{
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Id]
    #[Groups(['id'])]
    private ?UuidInterface $id = null;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }
}
