<?php

namespace App\Domain\__DOMAIN__\Entity;

use App\Domain\__DOMAIN__\Repository\__ENTITY__Repository;
use AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern\Identifiable;
use AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern\Timestampable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: __ENTITY__Repository::class)]
#[ORM\HasLifecycleCallbacks()]
class __ENTITY__
{
    use Identifiable;
    use Timestampable;
}
