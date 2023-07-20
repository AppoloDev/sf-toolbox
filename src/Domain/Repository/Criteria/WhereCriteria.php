<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\ExpressionInterface;

trait WhereCriteria
{
    public function in(string $field, array $params, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->in($field, $params, $customAlias));
    }

    public function notIn(string $field, array $params, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->notIn($field, $params, $customAlias));
    }

    public function isNull(string $field, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->isNull($field, $customAlias));
    }

    public function isNotNull(string $field, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->isNotNull($field, $customAlias));
    }

    public function eq(string $field, null|int|bool|string $value, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->eq($field, $value, $customAlias));
    }

    public function notEq(string $field, null|int|bool|string $value, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->notEq($field, $value, $customAlias));
    }

    public function between(string $field, ?string $from, ?string $to, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->between($field, $from, $to, $customAlias));
    }

    public function searchIntoFields(?string $query, array $fields, string $customAlias = null): self
    {
        return $this->complexQuery(fn (ComplexBuilder $cb) => $cb->searchIntoFields($query, $fields, $customAlias));
    }

    public function whereExpr(ExpressionInterface $selectExpression): self
    {
        $this->qb->where($selectExpression->toString($this));
        return $this;
    }

    public function orWhereExpr(ExpressionInterface $selectExpression): self
    {
        $this->qb->orWhere($selectExpression->toString($this));
        return $this;
    }

    public function andWhereExpr(ExpressionInterface $selectExpression): self
    {
        $this->qb->andWhere($selectExpression->toString($this));
        return $this;
    }
}
