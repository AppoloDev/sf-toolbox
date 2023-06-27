<?php

namespace App\Domain\__DOMAIN__\Repository;

use App\Domain\__DOMAIN__\Entity\__ENTITY__;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\GroupAndOrderCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\JoinCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\SelectCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\WhereCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<__ENTITY__>
*/
class __ENTITY__Repository extends ServiceEntityRepository
{
    use BuilderCriteria;
    use GroupAndOrderCriteria;
    use WhereCriteria;
    use JoinCriteria;
    use SelectCriteria;

    protected static string $alias = '__ALIAS__';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, __ENTITY__::class);
    }
}
