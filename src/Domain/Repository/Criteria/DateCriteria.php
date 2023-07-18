<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

// TODO : Refactor
trait DateCriteria
{
    public function date(string $field, \DateTimeInterface $date, string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $paramName = 'date'.$alias.$field.uniqid();

        $this->qb
            ->andWhere($this->qb->expr()->eq($alias.'.'.$field, ':'.$paramName))
            ->setParameter($paramName, $date);

        return $this;
    }

    public function dateBetween(
        string $field,
        \DateTimeInterface $from,
        \DateTimeInterface $to,
        string $customAlias = null
    ): self {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->between($alias.'.'.$field, ':from'.$field, ':to'.$field))
            ->setParameter('from'.$field, $from)
            ->setParameter('to'.$field, $to);

        return $this;
    }

    public function dateNotExpired(
        string $field,
        string $customAlias = null,
        \DateTimeInterface $customDate = null
    ): self {
        $currentDate = is_null($customDate) ? new \DateTimeImmutable() : $customDate;
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $paramName = 'date'.$alias.$field.uniqid();

        $this->qb
            ->andWhere($this->qb->expr()->gte($alias.'.'.$field, ':'.$paramName))
            ->setParameter($paramName, $currentDate);

        return $this;
    }

    public function dateExpired(
        string $field,
        string $customAlias = null,
        \DateTimeInterface $customDate = null
    ): self {
        $currentDate = is_null($customDate) ? new \DateTimeImmutable() : $customDate;
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $paramName = 'date'.$alias.$field.uniqid();

        $this->qb
            ->andWhere($this->qb->expr()->lte($alias.'.'.$field, ':'.$paramName))
            ->setParameter($paramName, $currentDate);

        return $this;
    }
}