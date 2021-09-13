<?php

namespace AppoloDev\SFToolbox\Doctrine\Repository\Criteria;

trait JoinCriteria
{
    public function with(string $field, string $joinAlias, ?string $customAlias = null, ?bool $addSelect = false): self
    {
        $alias = null !== $customAlias ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect($joinAlias);
        }

        $this->qb->leftJoin($alias.'.'.$field, $joinAlias);

        return $this;
    }
}
