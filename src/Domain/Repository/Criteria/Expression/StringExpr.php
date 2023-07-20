<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;

readonly class StringExpr implements ExpressionInterface
{
    public function __construct(private string $str)
    {
    }

    public function toString(BuilderCriteriaInterface $builderCriteria): string
    {
        return $this->str;
    }
}
