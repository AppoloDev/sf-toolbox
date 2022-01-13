<?php

namespace AppoloDev\SFToolbox\Doctrine\Repository\Criteria;

trait GroupAndOrderCriteria
{
    public function random(): self
    {
        $this->qb->orderBy('rand()');

        return $this;
    }

    public function order(string $field, string $direction = 'ASC', ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->addOrderBy($alias.'.'.$field, $direction);

        return $this;
    }

    public function groupBy(string $field, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->addGroupBy($alias.'.'.$field);

        return $this;
    }

    public function indexBy(string $field, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->indexBy($alias, $alias.'.'.$field);
        return $this;
    }
}
