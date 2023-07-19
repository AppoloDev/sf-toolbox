<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

trait PublishableCriteria
{
    public function published(
        string $fieldFrom = 'publicationStartDate',
        string $fieldTo = 'publicationEndDate',
        string $customAlias = null,
        \DateTime $currentDate = null,
    ): self {
        if (is_null($currentDate)) {
            $startDate = (new \DateTimeImmutable());
            $startDate->setTime(0, 0, 0, 0);
            $endDate = (new \DateTimeImmutable());
            $endDate->setTime(23, 59, 59, 59);
        } else {
            $startDate = $currentDate;
            $endDate = $currentDate;
        }
        $startDate->setTimezone(new \DateTimeZone('Europe/Paris'));
        $endDate->setTimezone(new \DateTimeZone('Europe/Paris'));

        $this->complexQuery(fn(ComplexBuilder $cb) => $cb->orX(
            $cb->lte($fieldFrom, $startDate, $customAlias),
            $cb->isNull($fieldFrom, $customAlias)
        ))
        ->complexQuery(fn(ComplexBuilder $cb) => $cb->orX(
            $cb->gte($fieldTo, $endDate, $customAlias),
            $cb->isNull($fieldTo, $customAlias)
        ));

        return $this;
    }
}
