<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

enum DoctrineOperator: string
{
    case LTE = 'lte';
    case LT = 'lt';
    case GTE = 'gte';
    case GT = 'gt';
    case NOT_IN = 'notIn';
    case IN = 'in';
    case EQ = 'eq';
    case NOT_EQ = 'neq';
}
