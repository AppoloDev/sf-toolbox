<?php

namespace App\Domain\##DOMAIN##\Repository;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\GroupAndOrderCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\JoinCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\SelectCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\WhereCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<##ENTITY##>
*/
class ##ENTITY##Repository extends ServiceEntityRepository
{
    use BuilderCriteria;
    use GroupAndOrderCriteria;
    use WhereCriteria;
    use JoinCriteria;
    use SelectCriteria;

    protected static string $alias = '##ENTITYLOWER##';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ##ENTITY##::class);
    }
}
