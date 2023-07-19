<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Utils\UuidUtils;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\Uid\Uuid;

class ComplexBuilder
{
    public function __construct(private readonly BuilderCriteriaInterface $builderCriteria)
    {
    }

    public function orX(Composite|Comparison|string|null ...$conditions): ?Composite
    {
        $cnds = array_filter($conditions, fn (mixed $cnd) => !is_null($cnd));
        if (0 === count($cnds)) {
            return null;
        }

        return $this->builderCriteria->getQueryBuilder()->expr()->orX(...$cnds);
    }

    public function searchIntoFields(?string $querySearch, array $fieldsToSearch, string $customAlias = null): ?Composite
    {
        $orX = $this->builderCriteria->getQueryBuilder()->expr()->orX();
        if (count($fieldsToSearch) > 0 && !is_null($querySearch) && '' !== $querySearch) {
            $qb = $this->builderCriteria->getMainQb();

            foreach (explode(' ', $querySearch) as $term) {
                $isUuid = UuidUtils::isUuid($term);

                foreach ($fieldsToSearch as $fieldToSearch) {
                    $aliasField = $this->builderCriteria->getAliasField($customAlias, $fieldToSearch);
                    $value = ('id' === $fieldToSearch && $isUuid ? Uuid::fromString($term)->toBinary() : $term);
                    $paramName = $fieldToSearch.uniqid();
                    $orX->add($this->builderCriteria->getQueryBuilder()->expr()->like('LOWER('.$aliasField.')', ':'.$paramName));
                    $orX->add($this->builderCriteria->getQueryBuilder()->expr()->like($aliasField, ':'.$paramName));
                    $qb->setParameter($paramName, $isUuid ? $value : '%'.strtolower($value).'%', $isUuid ? 'uuid' : null);
                }
            }

            return $orX;
        }

        return null;
    }

    public function andX(Composite|Comparison|string ...$conditions): ?Composite
    {
        $cnds = array_filter($conditions, fn (mixed $cnd) => !is_null($cnd));
        if (0 === count($cnds)) {
            return null;
        }

        return $this->builderCriteria->getQueryBuilder()->expr()->andX(...$cnds);
    }

    public function eq(string $field, null|int|bool|string $value, string $customAlias = null): Comparison|Func
    {
        return $this->comparisonOperator(DoctrineOperator::EQ, $field, $value, $customAlias);
    }

    public function notEq(string $field, string $value, string $customAlias = null): Comparison|Func
    {
        return $this->comparisonOperator(DoctrineOperator::NOT_EQ, $field, $value, $customAlias);
    }

    public function in(string $field, array|string $value, string $customAlias = null): Comparison|Func
    {
        return $this->comparisonOperator(DoctrineOperator::IN, $field, $value, $customAlias);
    }

    public function notIn(string $field, string|array $value, string $customAlias = null): Comparison|Func
    {
        return $this->comparisonOperator(DoctrineOperator::NOT_IN, $field, $value, $customAlias);
    }

    public function gte(string $field, string|\DateTimeInterface $value, string $customAlias = null): Comparison|Func
    {
        return $this->comparisonOperator(DoctrineOperator::GTE, $field, $value, $customAlias);
    }

    public function lte(string $field, string|\DateTimeInterface $value, string $customAlias = null): Comparison|Func
    {
        return $this->comparisonOperator(DoctrineOperator::LTE, $field, $value, $customAlias);
    }

    public function isNull(string $field, string $customAlias = null): string
    {
        $aliasField = $this->builderCriteria->getAliasField($customAlias, $field);

        return $this->builderCriteria->getQueryBuilder()->expr()->isNull($aliasField);
    }

    public function isNotNull(string $field, string $customAlias = null): string
    {
        $aliasField = $this->builderCriteria->getAliasField($customAlias, $field);

        return $this->builderCriteria->getQueryBuilder()->expr()->isNotNull($aliasField);
    }

    public function between(string $field, ?string $from, ?string $to, string $customAlias = null): ?string
    {
        if (is_null($from) || is_null($to)) {
            return null;
        }

        $aliasField = $this->builderCriteria->getAliasField($customAlias, $field);
        $fromParamName = 'from'.$field.uniqid();
        $toParamName = 'to'.$field.uniqid();
        $this->builderCriteria->getMainQb()
            ->setParameter($fromParamName, $from)
            ->setParameter($toParamName, $to);

        return $this->builderCriteria->getQueryBuilder()
            ->expr()->between($aliasField, ':'.$fromParamName, ':'.$toParamName);
    }

    public function comparisonOperator(DoctrineOperator $operator,
        string $field,
        null|array|string|bool|int|\DateTimeInterface $value,
        string $customAlias = null): Comparison|Func
    {
        $aliasField = $this->builderCriteria->getAliasField($customAlias, $field);
        $paramName = 'value'.$field.uniqid();

        $this->builderCriteria->getMainQb()
            ->setParameter($paramName,
                is_iterable($value)
                            ? array_map(fn (mixed $val) => $this->builderCriteria->getValue($val), $value)
                            : $this->builderCriteria->getValue($value)
            );

        return $this->builderCriteria->getQueryBuilder()->expr()->{$operator->value}($aliasField, ':'.$paramName);
    }
}
