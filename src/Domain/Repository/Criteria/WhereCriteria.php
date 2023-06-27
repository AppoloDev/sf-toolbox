<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Utils\UuidUtils;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Component\Uid\Uuid;

trait WhereCriteria
{
    public function searchIntoFields(?string $querySearch, array $fieldsToSearch, string $customAlias = null): self
    {
        if (count($fieldsToSearch) > 0 && !is_null($querySearch) && '' !== $querySearch) {
            $alias = !is_null($customAlias) ? $customAlias : self::$alias;
            $andX = $this->qb->expr()->andX();
            $this->aggregateOrField($andX, $alias, $querySearch, $fieldsToSearch);
            $this->qb->andWhere($andX);
        }

        return $this;
    }

    public function orSearchIntoFields(?string $querySearch, array $fieldsToSearch, string $customAlias = null): self
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
                $paramName = 'likeSearchInto'.$alias.$fieldToSearch.uniqid();
                $orX->add($this->qb->expr()->like('LOWER('.$alias.'.'.$fieldToSearch.')', ':'.$paramName));
                $orX->add($this->qb->expr()->like($alias.'.'.$fieldToSearch, ':'.$paramName));
                $this->qb->setParameter($paramName, '%'.strtolower($value).'%', $isUuid ? 'uuid' : null);
            }

            $composite->add($orX);
        }
    }

    public function in(string $field, array $params, string $customAlias = null): self
    {
        if (count($params) > 0) {
            $params = array_map(function (mixed $param) {
                $isUuid = UuidUtils::isUuid($param);

                return $isUuid ? Uuid::fromString($param)->toBinary() : $param;
            }, $params);

            $alias = !is_null($customAlias) ? $customAlias : self::$alias;
            $paramName = 'inParam'.$alias.$field.uniqid();
            $this->qb
                ->andWhere($this->qb->expr()->in($alias.'.'.$field, ':'.$paramName))
                ->setParameter($paramName, $params);
        }

        return $this;
    }

    public function notIn(string $field, array $params, string $customAlias = null): self
    {
        if (count($params) > 0) {
            $params = array_map(function (mixed $param) {
                $isUuid = UuidUtils::isUuid($param);

                return $isUuid ? Uuid::fromString($param)->toBinary() : $param;
            }, $params);

            $alias = !is_null($customAlias) ? $customAlias : self::$alias;
            $paramName = 'notInParam'.$alias.$field.uniqid();
            $this->qb
                ->andWhere($this->qb->expr()->notIn($alias.'.'.$field, ':'.$paramName))
                ->setParameter($paramName, $params);
        }

        return $this;
    }

    public function isNull(string $field, string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->isNull($alias.'.'.$field));

        return $this;
    }

    public function isNotNull(string $field, string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->isNotNull($alias.'.'.$field));

        return $this;
    }

    public function eq(string $field, null|int|bool|string $value, string $customAlias = null): self
    {
        if (is_null($value) || '' === $value) {
            return $this;
        }

        $isUuid = UuidUtils::isUuid($value);
        if ($isUuid) {
            $value = Uuid::fromString($value)->toBinary();
        }

        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $paramName = 'value'.$alias.$field.uniqid();
        $this->qb
            ->andWhere($this->qb->expr()->eq($alias.'.'.$field, ':'.$paramName))
            ->setParameter($paramName, $value, $isUuid ? 'uuid' : null);

        return $this;
    }

    public function notEq(string $field, null|int|bool|string $value, string $customAlias = null): self
    {
        if (is_null($value)) {
            return $this;
        }

        $isUuid = UuidUtils::isUuid($value);
        if ($isUuid) {
            $value = Uuid::fromString($value)->toBinary();
        }

        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $paramName = 'value'.$alias.$field.uniqid();
        $this->qb
            ->andWhere($this->qb->expr()->neq($alias.'.'.$field, ':'.$paramName))
            ->setParameter($paramName, $value, $isUuid ? 'uuid' : null);

        return $this;
    }

    public function comparisonOperator(string $field, string $operator, string $value, string $customAlias = null): self
    {
        $alias = null !== $customAlias ? $customAlias : self::$alias;
        $paramName = 'value'.$alias.$field.uniqid();
        $this->qb
            ->andWhere($this->qb->expr()->$operator($alias.'.'.$field, ':'.$paramName))
            ->setParameter($paramName, $value);

        return $this;
    }

    public function between(string $field, ?string $from, ?string $to, string $customAlias = null): self
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

    public function around(float $lat, float $lng, int $radius = 10, string $customAlias = null, ?string $latField = 'lat', ?string $lngField = 'lng'): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $sqlDistance = "(6378 * acos(cos(radians({$lat})) * cos(radians({$alias}.{$latField})) * cos(radians({$alias}.{$lngField}) - radians({$lng})) + sin(radians({$lat})) * sin(radians({$alias}.{$latField}))))";

        $this->qb
            ->andWhere($this->qb->expr()->lt($sqlDistance, ':radius'))
            ->setParameter('radius', $radius);

        return $this;
    }

    public function bounds(array $bounds, string $customAlias = null, ?string $latField = 'lat', ?string $lngField = 'lng'): self
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
