<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

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
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->select($alias.'.'.$field);

        return $this;
    }

    public function addSelect(string $alias): self
    {
        $this->qb->addSelect($alias);

        return $this;
    }

    public function selectDistinct(string $field, string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->select($alias.'.'.$field)->distinct();

        return $this;
    }

    public function selectFromFunction(string $function, string $field, string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect($function.'('.$alias.'.'.$field.')');
        } else {
            $this->qb->select($function.'('.$alias.'.'.$field.')');
        }

        return $this;
    }
}
