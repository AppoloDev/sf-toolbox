<?php

namespace App\Domain\Shared\Criteria;

use App\Infrastructure\Utils\UuidUtils;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Uid\Uuid;

/**
 * @deprecated
 */
trait Criteria
{
    protected QueryBuilder $qb;

    /***************************************
     *
     * QUERY BUILDER
     *
     ***************************************/
    public function getQB(?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb = $this->createQueryBuilder($alias);

        return $this;
    }

    public function getBuilder(): QueryBuilder
    {
        return $this->qb;
    }

    /***************************************
     *
     * SELECT
     *
     ***************************************/
    public function max(string $field, ?string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect('MAX('.$alias.'.'.$field.')');
        } else {
            $this->qb->select('MAX('.$alias.'.'.$field.')');
        }

        return $this;
    }

    public function countItem(string $field, ?string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect('COUNT('.$alias.'.'.$field.')');
        } else {
            $this->qb->select('COUNT('.$alias.'.'.$field.')');
        }

        return $this;
    }

    public function sum(string $field, ?string $customAlias = null, bool $addSelect = false): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect('SUM('.$alias.'.'.$field.')');
        } else {
            $this->qb->select('SUM('.$alias.'.'.$field.')');
        }

        return $this;
    }

    public function addSelect(string $alias): self
    {
        $this->qb->addSelect($alias);

        return $this;
    }

    /***************************************
     *
     * JOIN CLAUSES
     *
     ***************************************/

    public function with(string $field, string $joinAlias, ?string $customAlias = null, ?bool $addSelect = false): self
    {
        $alias = null !== $customAlias ? $customAlias : self::$alias;

        if ($addSelect) {
            $this->qb->addSelect($joinAlias);
        }

        $this->qb->leftJoin($alias.'.'.$field, $joinAlias);

        return $this;
    }

    /***************************************
     *
     * WHERE CLAUSES
     *
     ***************************************/
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

    public function orSearchIntoJsonFields(?string $querySearch, array $fieldsToSearch, ?string $customAlias = null): self
    {
        if (count($fieldsToSearch) > 0 && !is_null($querySearch) && '' !== $querySearch) {
            $alias = !is_null($customAlias) ? $customAlias : self::$alias;

            $orX = $this->qb->expr()->orX();

            $this->aggregateOrJsonField($orX, $alias, $querySearch, $fieldsToSearch);

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
                $orX->add($this->qb->expr()->like($alias.'.'.$fieldToSearch, ':likeSearchInto'.$paramName));
                $this->qb->setParameter('likeSearchInto'.$paramName, '%'.$value.'%');
            }

            $composite->add($orX);
        }
    }

    private function aggregateOrJsonField(Composite $composite, string $alias, string $querySearch, array $fieldsToSearch): void
    {
        foreach (explode(' ', $querySearch) as $term) {
            $orX = $this->qb->expr()->orX();

            foreach ($fieldsToSearch as $fieldToSearch) {
                $paramName = $alias.$fieldToSearch;
                $orX->add($this->qb->expr()->like('LOWER('.$alias.'.'.$fieldToSearch.')', ':likeSearchIntoJson'.$paramName));
                // AFAIRE: JSON_CONTAINS => https://stackoverflow.com/questions/36249828/how-to-search-json-array-in-mysql
                $this->qb->setParameter('likeSearchIntoJson'.$paramName, '%'.strtolower($term).'%');
            }

            $composite->add($orX);
        }
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

    public function is(string $field, bool $is, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->eq($alias.'.'.$field, ':isField'.$field))
            ->setParameter('isField'.$field, $is);

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

    public function eq(string $field, null|int|string $value, ?string $customAlias = null): self
    {
        if (is_null($value)) {
            return $this;
        }

        $alias = !is_null($customAlias) ? $customAlias : self::$alias;
        $this->qb
            ->andWhere($this->qb->expr()->eq($alias.'.'.$field, ':value'.$field))
            ->setParameter('value'.$field, $value);

        return $this;
    }

    public function notEq(string $field, null|int|string $value, ?string $customAlias = null): self
    {
        if (is_null($value)) {
            return $this;
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

    public function date(string $field, DateTime $date, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->eq($alias.'.'.$field, ':date'.$field))
            ->setParameter('date'.$field, $date);

        return $this;
    }

    public function dateBetween(string $field, DateTime $from, DateTime $to, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere($this->qb->expr()->between($alias.'.'.$field, ':from'.$field, ':to'.$field))
            ->setParameter('from'.$field, $from)
            ->setParameter('to'.$field, $to);

        return $this;
    }

    public function dateNotExpired(
        string $field,
        ?string $customAlias = null
    ): self {
        $currentDate = (new DateTimeImmutable());
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere(
                $this->qb->expr()->gte($alias.'.'.$field, ':date'.$field))
            ->setParameter('date'.$field, $currentDate->setTime(0, 0, 0, 0));

        return $this;
    }

    public function dateExpired(
        string $field,
        ?string $customAlias = null
    ): self {
        $currentDate = (new DateTimeImmutable());
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb
            ->andWhere(
                $this->qb->expr()->lte($alias.'.'.$field, ':date'.$field))
            ->setParameter('date'.$field, $currentDate->setTime(0, 0, 0, 0));

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

    public function eqBinary(string $field, ?string $id, ?string $customAlias = null): self
    {
        if (!is_null($id)) {
            $binary = Uuid::fromString($id)->toBinary();
            $this->eq($field, $binary, $customAlias);
        }

        return $this;
    }

    /***************************************
     *
     * ORDER
     *
     ***************************************/
    public function random(): self
    {
        $this->qb->orderBy('rand()');

        return $this;
    }

    public function order(string $field, string $direction = 'ASC', ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->addOrderBy($alias.'.'.$field, $direction);

        return $this;
    }

    public function groupBy(string $field, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->addGroupBy($alias.'.'.$field);

        return $this;
    }

    private function getAndGroupByDate(string $field, ?string $customAlias = null): self
    {
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $this->qb->select('COUNT('.$alias.'.'.$field.') as total, DATE_FORMAT('.$alias.'.'.$field.', \'%Y-%m-%d\') as date')
            ->groupBy('date')
            ->orderBy('date');

        return $this;
    }

    /***************************************
     *
     * Limit
     *
     ***************************************/
    public function limit(int $limit): self
    {
        $this->qb->setMaxResults($limit);

        return $this;
    }

    /***************************************
     *
     * QUERY
     *
     ***************************************/
    public function getSingleResult(): ?object
    {
        $this->qb->setMaxResults(1);
        $results = $this->getResults();

        return isset($results[0]) ? $results[0] : null;
    }

    public function getSingleScalarResult(): ?int
    {
        return (int) $this->qb->getQuery()->getSingleScalarResult();
    }

    public function getResults(): array
    {
        return $this->qb->getQuery()->getResult();
    }
}
