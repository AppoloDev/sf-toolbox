<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

trait GroupAndOrderCriteria
{
    public function random(): self
    {
        $this->qb->orderBy('rand()');

        return $this;
    }

    public function order(string $field, string $direction = 'ASC', string $customAlias = null): self
    {
        $aliasField = $this->getAliasField($customAlias, $field);

        $this->qb->addOrderBy($aliasField, $direction);

        return $this;
    }

    public function groupBy(string $field, string $customAlias = null): self
    {
        $aliasField = $this->getAliasField($customAlias, $field);

        $this->qb->addGroupBy($aliasField);

        return $this;
    }

    public function indexBy(string $field, string $customAlias = null): self
    {
        $alias = $this->getAlias($customAlias);

        $this->qb->indexBy($alias, $alias.'.'.$field);

        return $this;
    }
}
