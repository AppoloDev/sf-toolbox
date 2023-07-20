<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use DateTime;
use DateTimeImmutable;

trait PublishableCriteria
{
    public function published(
        string   $fieldFrom = 'publicationStartDate',
        string   $fieldTo = 'publicationEndDate',
        string   $customAlias = null,
        DateTime $currentDate = null,
    ): self
    {
        if (is_null($currentDate)) {
            $startDate = (new DateTimeImmutable())->setTime(0, 0, 0, 0);
            $endDate = (new DateTimeImmutable())->setTime(23, 59, 59, 59);
        } else {
            $startDate = $currentDate;
            $endDate = $currentDate;
        }

        $this->complexQuery(fn(ComplexBuilder $cb) => $cb->andX(
            $cb->orX(
                $cb->lte($fieldFrom, $startDate, $customAlias),
                $cb->isNull($fieldFrom, $customAlias)
            ),
            $cb->orX(
                $cb->gte($fieldTo, $endDate, $customAlias),
                $cb->isNull($fieldTo, $customAlias)
            )
        ));

        return $this;
    }
}
