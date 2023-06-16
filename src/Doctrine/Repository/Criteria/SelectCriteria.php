<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Repository\Criteria;

trait SelectCriteria
{
    public function max(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect('MAX('.$alias.'.'.$field.')');
        } else {
            $this->qb->select('MAX('.$alias.'.'.$field.')');
        }

        return $this;
    }

    public function countItem(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect('COUNT('.$alias.'.'.$field.')');
        } else {
            $this->qb->select('COUNT('.$alias.'.'.$field.')');
        }

        return $this;
    }

    public function sum(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect('SUM('.$alias.'.'.$field.')');
        } else {
            $this->qb->select('SUM('.$alias.'.'.$field.')');
        }

        return $this;
    }

    public function select(string $field, string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->select($alias.'.'.$field);

        return $this;
    }

    public function selectDistinct(string $field, string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->select($alias.'.'.$field)->distinct();

        return $this;
    }

    public function addSelect(string $alias): self
    {
        $this->qb->addSelect($alias);

        return $this;
    }
}
