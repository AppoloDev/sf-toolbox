<?php

namespace App\Domain\##DOMAIN##\Entity;

use App\Domain\##DOMAIN##\Repository\##ENTITY##Repository;
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable;
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Timestampable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ##ENTITY##Repository::class)]
#[ORM\HasLifecycleCallbacks()]
class ##ENTITY##
{
    use Identifiable;
    use Timestampable;
}
