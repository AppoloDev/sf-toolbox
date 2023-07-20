<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\ExpressionInterface;

trait SelectCriteria
{
    public function max(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        return $this->selectFromFunction('MAX', $field, $customAlias, $addSelect);
    }

    public function min(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        return $this->selectFromFunction('MIN', $field, $customAlias, $addSelect);
    }

    public function countItem(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        return $this->selectFromFunction('COUNT', $field, $customAlias, $addSelect);
    }

    public function sum(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        return $this->selectFromFunction('SUM', $field, $customAlias, $addSelect);
    }

    public function select(string $field, string $customAlias = null): self
    {
        $this->qb->select($this->getAliasField($customAlias, $field));

        return $this;
    }

    public function addSelect(string $field, string $customAlias = null): self
    {
        $this->qb->addSelect($this->getAliasField($customAlias, $field));

        return $this;
    }

    public function selectDistinct(string $field, string $customAlias = null): self
    {
        $this->qb->select($this->getAliasField($customAlias, $field))->distinct();

        return $this;
    }

    public function selectExpr(ExpressionInterface $selectExpression): self
    {
        $this->qb->select($selectExpression->toString($this));
        return $this;
    }

    public function selectFromSubQuery(string $entityClass, string $alias, callable $cb, string $subSelectAlias = null): self
    {
        // TODO: documentation
        $rep = (clone $this->_em->getRepository($entityClass));
        $dql = $cb($rep->getSubQb($this->qb, $alias))->getBuilder()->getQuery()->getDQL();
        $this->qb->addSelect('('.$dql.')'.($subSelectAlias ? " as $subSelectAlias" : ''));

        return $this;
    }

    public function selectFromFunction(string $function, string $field, string $customAlias = null, bool $addSelect = false): self
    {
        $aliasField = $this->getAliasField($customAlias, $field);

        if ($addSelect) {
            $this->qb->addSelect($function.'('.$aliasField.')');
        } else {
            $this->qb->select($function.'('.$aliasField.')');
        }

        return $this;
    }
}
