<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use Doctrine\ORM\Query\Expr\Join;

trait JoinCriteria
{
    public function with(string $field, string $joinAlias, string $customAlias = null, ?bool $addSelect = false): self
    {
        if ($addSelect) {
            $this->qb->addSelect($joinAlias);
        }

        $this->qb->leftJoin($this->getAliasField($customAlias, $field), $joinAlias);

        return $this;
    }

    public function join(string $className, string $joinAlias, string $conditions): self
    {
        $this->qb->join($className, $joinAlias, Join::WITH, $conditions);

        return $this;
    }

    public function leftJoin(string $className, string $joinAlias, string $conditions): self
    {
        $this->qb->leftJoin($className, $joinAlias, Join::WITH, $conditions);

        return $this;
    }
}
