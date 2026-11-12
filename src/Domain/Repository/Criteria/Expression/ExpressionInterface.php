<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;

interface ExpressionInterface
{
    public function toString(BuilderCriteriaInterface $builderCriteria): string;
}
