<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\Select;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\ExpressionInterface;

readonly class PartialDtoExpr implements ExpressionInterface
{
    public function __construct(
        private array $fields,
        private string $objectClass,
        private ?string $customAlias = null
    ) {
    }

    public function toString(BuilderCriteriaInterface $builderCriteria): string
    {
        $alias = $builderCriteria->getAlias($this->customAlias);
        $fields = join(', ', array_map(fn (string $field) => $alias.'.'.$field, $this->fields));

        return sprintf("NEW %s($fields)", $this->objectClass);
    }
}
