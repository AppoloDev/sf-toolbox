<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

trait DateCriteria
{
    public function dateBetween(
        string $field,
        \DateTimeInterface $from,
        \DateTimeInterface $to,
        string $customAlias = null
    ): self { // TODO => Useless :/
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->between($field, $from, $to, $customAlias));
    }

    public function dateNotExpired(
        string $field,
        \DateTimeInterface $customDate = null,
        string $customAlias = null,
    ): self {
        $date = is_null($customDate) ? new \DateTimeImmutable() : $customDate;
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->comparisonOperator(DoctrineOperator::GTE, $field, $date, $customAlias));
    }

    public function dateExpired(
        string $field,
        \DateTimeInterface $customDate = null,
        string $customAlias = null,
    ): self {
        $date = is_null($customDate) ? new \DateTimeImmutable() : $customDate;
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->comparisonOperator(DoctrineOperator::LTE, $field, $date, $customAlias));
    }
}
