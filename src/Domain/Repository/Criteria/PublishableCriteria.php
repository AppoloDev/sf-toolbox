<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

// TODO: Refactor
trait PublishableCriteria
{
    public function published(
        string $fieldFrom = 'publicationStartDate',
        string $fieldTo = 'publicationEndDate',
        string $customAlias = null,
        \DateTime $currentDate = null,
    ): self {
        // TODO: PublishableCriteria
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

        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->orX(
                $this->qb->expr()->lte($alias.'.'.$fieldFrom, ':publishedFieldStart'),
                $this->qb->expr()->isNull($alias.'.'.$fieldFrom)
            ))
            ->andWhere($this->qb->expr()->orX(
                $this->qb->expr()->gte($alias.'.'.$fieldTo, ':publishedFieldEnd'),
                $this->qb->expr()->isNull($alias.'.'.$fieldTo)
            ))
            ->setParameter('publishedFieldStart', $startDate)
            ->setParameter('publishedFieldEnd', $endDate);

        return $this;
    }
}