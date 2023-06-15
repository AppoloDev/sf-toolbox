<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

trait Identifiable
{
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Id]
    #[Groups(['id'])]
    private ?Uuid $id = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
