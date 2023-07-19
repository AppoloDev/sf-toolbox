<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use Doctrine\ORM\QueryBuilder;

interface BuilderCriteriaInterface
{
    public function getValue(mixed $value): mixed;

    public function getAlias(?string $customAlias): string;

    public function getAliasField(?string $customAlias, string $field): string;

    public function getQueryBuilder(): QueryBuilder;

    public function getMainQb(): QueryBuilder;
}
