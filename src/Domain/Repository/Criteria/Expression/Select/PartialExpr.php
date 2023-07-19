<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\Select;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\ExpressionInterface;

class PartialExpr implements ExpressionInterface
{
    public function __construct(private readonly array $fields, private readonly ?string $customAlias = null)
    {
    }

    public function toString(BuilderCriteriaInterface $builderCriteria): string
    {
        return 'partial ' . $builderCriteria->getAlias($this->customAlias) . '.{' . join(', ', $this->fields) . '}';
    }
}
