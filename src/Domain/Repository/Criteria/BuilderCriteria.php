<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable\IdentifiableInterface;
use AppoloDev\SFToolboxBundle\Utils\UuidUtils;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Uid\Uuid;

trait BuilderCriteria
{
    protected QueryBuilder $qb;
    protected ?QueryBuilder $parentQb = null;

    public function getQB(string $customAlias = null): self
    {
        $this->qb = $this->createQueryBuilder($this->getAlias($customAlias));

        return $this;
    }

    public function getSubQb(QueryBuilder $qb, string $alias): self
    {
        $this->qb = $this->createQueryBuilder($alias);
        $this::$alias = $alias;
        $this->parentQb = $qb;

        return $this;
    }

    public function getBuilder(): QueryBuilder
    {
        return $this->qb;
    }

    public function useQBMethod(string $methodName, array $params = []): self
    {
        $this->qb->$methodName(...$params);

        // TODO: Bug quand il est tête

        return $this;
    }

    public function update(string $field, null|int|bool|string $value, string $customAlias = null): self
    {
        $this->qb->update()->set($this->getAliasField($customAlias, $field), $value);

        return $this;
    }

    public function delete(): self
    {
        $this->qb->delete();

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->qb->setMaxResults($limit);

        return $this;
    }

    public function getSingleResult(): ?object
    {
        $this->qb->setMaxResults(1);
        $results = $this->getResults();

        return $results[0] ?? null;
    }

    public function getSingleScalarResult(): ?int
    {
        return (int) $this->qb->getQuery()->getSingleScalarResult();
    }

    public function getResults(): array
    {
        return $this->qb->getQuery()->getResult();
    }

    public function getArrayResults(): array
    {
        return $this->qb->getQuery()->getArrayResult();
    }

    public function getResultsIndexedBy(string $field): array
    {
        $results = array_reduce($this->getQB()->getResults(), function (?array $acc, mixed $item) use ($field) {
            $acc[$item->{'get'.ucfirst($field)}()] = $item;

            return $acc;
        });

        return is_null($results) ? [] : $results;
    }

    public function getResultsIndexedById(): array
    {
        $results = array_reduce($this->getQB()->getResults(), function (?array $acc, IdentifiableInterface $item) {
            $acc[$item->getId()->__toString()] = $item;

            return $acc;
        });

        return is_null($results) ? [] : $results;
    }

    public function getParentQueryBuilder(): ?QueryBuilder
    {
        return $this->parentQb;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->qb;
    }

    public function complexQuery(callable $cb): self
    {
        $complexBuilder = new ComplexBuilder($this);
        $cmp = $cb($complexBuilder);

        if (!is_null($cmp)) {
            $this->qb->andWhere($cmp);
        }

        return $this;
    }

    public function getAlias(?string $customAlias): string
    {
        return null !== $customAlias ? $customAlias : self::$alias;
    }

    public function getAliasField(?string $customAlias, string $field): string
    {
        return $this->getAlias($customAlias).'.'.$field;
    }

    public function setParameter(string $paramName, mixed $value): self
    {
        $this->getMainQb()->setParameter($paramName, $value);

        return $this;
    }

    public function getValue(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $isUuid = UuidUtils::isUuid($value);
        if ($isUuid) {
            return Uuid::fromString($value)->toBinary();
        }

        return $value;
    }

    public function getMainQb(): QueryBuilder
    {
        return $this->parentQb ?? $this->qb;
    }
}
