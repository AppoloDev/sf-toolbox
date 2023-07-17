<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

trait JoinCriteria
{
    // TODO: Add Identity
    public function with(string $field, string $joinAlias, string $customAlias = null, ?bool $addSelect = false): self
    {
        $alias = null !== $customAlias ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect($joinAlias);
        }

        $this->qb->leftJoin($alias.'.'.$field, $joinAlias);

        return $this;
    }
}
