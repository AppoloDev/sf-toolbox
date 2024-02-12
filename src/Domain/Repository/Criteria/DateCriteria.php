<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

trait DateCriteria
{
    public function date(
        string $field,
        \DateTimeInterface $date,
        string $customAlias = null
    ): self {
        if ($date instanceof \DateTime) {
            $immutable = \DateTimeImmutable::createFromMutable($date);
        } else {
            $immutable = $date;
        }

        $start = $immutable->setTime(0, 0, 0);
        $end = $immutable->setTime(23, 59, 59);

        return $this->dateBetween($field, $start, $end, $customAlias);
    }

    public function dateBetween(
        string $field,
        \DateTimeInterface $from,
        \DateTimeInterface $to,
        string $customAlias = null
    ): self {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->between(
            $field,
            $from->format('Y-m-d H:i:s'),
            $to->format('Y-m-d H:i:s'),
            $customAlias
        ));
    }

    public function dateNotExpired(
        string $field,
        \DateTimeInterface $customDate = null,
        string $customAlias = null,
    ): self {
        $date = $customDate ?? new \DateTimeImmutable();

        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->gte($field, $date, $customAlias));
    }

    public function dateExpired(
        string $field,
        \DateTimeInterface $customDate = null,
        string $customAlias = null,
    ): self {
        $date = $customDate ?? new \DateTimeImmutable();

        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->lte($field, $date, $customAlias));
    }
}
