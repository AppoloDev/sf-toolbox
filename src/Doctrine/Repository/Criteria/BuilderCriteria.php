<?php

namespace AppoloDev\SFToolbox\Doctrine\Repository\Criteria;

use Doctrine\ORM\QueryBuilder;

trait BuilderCriteria
{
    protected QueryBuilder $qb;

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

    public function useQBMethod(string $methodName, array $params = []): self
    {
        $this->qb->$methodName(...$params);

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
}
