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
        $this->qb->addOrderBy($this->getAliasField($customAlias, $field), $direction);

        return $this;
    }

    public function groupBy(string $field, string $customAlias = null): self
    {
        $this->qb->addGroupBy($this->getAliasField($customAlias, $field));

        return $this;
    }

    public function indexBy(string $field, string $customAlias = null): self
    {
        $this->qb->indexBy($this->getAlias($customAlias), $this->getAliasField($customAlias, $field));

        return $this;
    }
}
