<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait Sluggable
{
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['slug'])]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateSlug(): void
    {
        if (method_exists($this, 'getTitle')) {
            $slugger = new AsciiSlugger();
            $this->setSlug(strtolower($slugger->slug($this->getTitle())));
        }
    }
}
