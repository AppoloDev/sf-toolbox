<?php

namespace AppoloDev\SFToolbox\Doctrine\Repository\Criteria;

use AppoloDev\SFToolbox\Utils\UuidUtils;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Component\Uid\Uuid;

trait WhereCriteria
{
    public function searchIntoFields(?string $querySearch, array $fieldsToSearch, ?string $customAlias = null): self
    {
        if (count($fieldsToSearch) > 0 && !is_null($querySearch) && '' !== $querySearch) {
            $alias = !is_null($customAlias) ? $customAlias : self::$alias;
            $andX = $this->qb->expr()->andX();
            $this->aggregateOrField($andX, $alias, $querySearch, $fieldsToSearch);
            $this->qb->andWhere($andX);
        }

        return $this;
    }

    public function orSearchIntoFields(?string $querySearch, array $fieldsToSearch, ?string $customAlias = null): self
    {
        if (count($fieldsToSearch) > 0 && !is_null($querySearch) && '' !== $querySearch) {
            $alias = !is_null($customAlias) ? $customAlias : self::$alias;
            $orX = $this->qb->expr()->orX();
            $this->aggregateOrField($orX, $alias, $querySearch, $fieldsToSearch);
            $this->qb->orWhere($orX);
        }

        return $this;
    }

    private function aggregateOrField(Composite $composite, string $alias, string $querySearch, array $fieldsToSearch): void
    {
        foreach (explode(' ', $querySearch) as $term) {
            $orX = $this->qb->expr()->orX();
            $isUuid = UuidUtils::isUuid($term);

            foreach ($fieldsToSearch as $fieldToSearch) {
                $value = ('id' === $fieldToSearch && $isUuid ? Uuid::fromString($term)->toBinary() : $term);
                $paramName = $alias.$fieldToSearch;
                $orX->add($this->qb->expr()->like('LOWER('.$alias.'.'.$fieldToSearch.')', ':likeSearchInto'.$paramName));
                $orX->add($this->qb->expr()->like($alias.'.'.$fieldToSearch, ':likeSearchInto'.$paramName));
                $this->qb->setParameter('likeSearchInto'.$paramName, '%'.strtolower($value).'%');
            }

            $composite->add($orX);
        }
    }

    public function in(string $field, array $params, ?string $customAlias = null): self
    {
        if (count($params) > 0) {
            $alias = !is_null($customAlias) ? $customAlias : self::$alias;
            $this->qb
                ->andWhere($this->qb->expr()->in($alias.'.'.$field, ':inParam'.$field))
                ->setParameter('inParam'.$field, $params);
        }

        return $this;
    }

    public function notIn(string $field, array $params, ?string $customAlias = null): self
    {
        if (count($params) > 0) {
            $alias = !is_null($customAlias) ? $customAlias : self::$alias;
            $this->qb
                ->andWhere($this->qb->expr()->notIn($alias.'.'.$field, ':notInParam'.$field))
                ->setParameter('notInParam'.$field, $params);
        }

        return $this;
    }

    public function isNull(string $field, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->isNull($alias.'.'.$field));

        return $this;
    }

    public function isNotNull(string $field, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->isNotNull($alias.'.'.$field));

        return $this;
    }

    public function eq(string $field, null|int|bool|string $value, ?string $customAlias = null): self
    {
        if (is_null($value) || '' === $value) {
            return $this;
        }

        $isUuid = UuidUtils::isUuid($value);
        if ($isUuid) {
            $value = Uuid::fromString($value)->toBinary();
        }

        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->eq($alias.'.'.$field, ':value'.$field))
            ->setParameter('value'.$field, $value);

        return $this;
    }

    public function notEq(string $field, null|int|bool|string $value, ?string $customAlias = null): self
    {
        if (is_null($value)) {
            return $this;
        }

        $isUuid = UuidUtils::isUuid($value);
        if ($isUuid) {
            $value = Uuid::fromString($value)->toBinary();
        }

        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->neq($alias.'.'.$field, ':value'.$field))
            ->setParameter('value'.$field, $value);

        return $this;
    }

    public function comparisonOperator(string $field, string $operator, string $value, ?string $customAlias = null): self
    {
        $alias = null !== $customAlias ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->$operator($alias.'.'.$field, ':value'.$field))
            ->setParameter('value'.$field, $value)
        ;

        return $this;
    }

    public function between(string $field, ?string $from, ?string $to, ?string $customAlias = null): self
    {
        if (is_null($from) || is_null($to)) {
            return $this;
        }

        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->between($alias.'.'.$field, ':from'.$field, ':to'.$field))
            ->setParameter('from'.$field, $from)
            ->setParameter('to'.$field, $to);

        return $this;
    }

    public function date(string $field, DateTimeInterface $date, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->eq($alias.'.'.$field, ':date'.$field))
            ->setParameter('date'.$field, $date);

        return $this;
    }

    public function dateBetween(
        string $field,
        DateTimeInterface $from,
        DateTimeInterface $to,
        ?string $customAlias = null
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
        ?string $customAlias = null,
        ?DateTimeInterface $customDate = null
    ): self {
        $currentDate = is_null($customDate) ? new DateTimeImmutable() : $customDate;
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->gte($alias.'.'.$field, ':date'.$field))
            ->setParameter('date'.$field, $currentDate);

        return $this;
    }

    public function dateExpired(
        string $field,
        ?string $customAlias = null,
        ?DateTimeInterface $customDate = null
    ): self {
        $currentDate = is_null($customDate) ? new DateTimeImmutable() : $customDate;
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->lte($alias.'.'.$field, ':date'.$field))
            ->setParameter('date'.$field, $currentDate);

        return $this;
    }

    public function published(
        string $fieldFrom = 'publicationStartDate',
        string $fieldTo = 'publicationEndDate',
        ?string $customAlias = null
    ): self {
        $currentDate = (new DateTimeImmutable());
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
            ->setParameter('publishedFieldStart', $currentDate->setTime(0, 0, 0, 0))
            ->setParameter(
                'publishedFieldEnd',
                $currentDate->setTime(23, 59, 59, 59)
            );

        return $this;
    }

    public function around(float $lat, float $lng, int $radius = 10, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $sqlDistance = "(6378 * acos(cos(radians({$lat})) * cos(radians({$alias}.lat)) * cos(radians({$alias}.lng) - radians({$lng})) + sin(radians({$lat})) * sin(radians({$alias}.lat))))";

        $this->qb
            ->andWhere($this->qb->expr()->lt($sqlDistance, ':radius'))
            ->setParameter('radius', $radius);

        return $this;
    }

    public function bounds(array $bounds, ?string $customAlias = null, ?string $latField = 'lat', ?string $lngField = 'lng'): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if (isset($bounds['slat']) && isset($bounds['nlat'])) {
            $this->qb
                ->andWhere($this->qb->expr()->between($alias.'.'.$latField, ':fromLat', ':toLat'))
                ->setParameter('fromLat', (float) $bounds['slat'])
                ->setParameter('toLat', (float) $bounds['nlat']);
        }
        if (isset($bounds['slng']) && isset($bounds['nlng'])) {
            $this->qb
                ->andWhere($this->qb->expr()->between($alias.'.'.$lngField, ':fromLng', ':toLng'))
                ->setParameter('fromLng', (float) $bounds['slng'])
                ->setParameter('toLng', (float) $bounds['nlng']);
        }

        return $this;
    }
}
