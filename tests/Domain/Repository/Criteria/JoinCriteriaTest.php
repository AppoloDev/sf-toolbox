<?php

declare(strict_types=1);

namespace AppoloDev\SFToolboxBundle\Tests\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\JoinCriteria;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class JoinCriteriaTest extends TestCase
{
    public function testJoinUsesOnConditionType(): void
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects(self::once())
            ->method('join')
            ->with('App\Entity\Order', 'o', Join::ON, 'o.book = book.id')
            ->willReturnSelf();

        $subject = $this->createJoinCriteria($queryBuilder);

        self::assertSame($subject, $subject->join('App\Entity\Order', 'o', 'o.book = book.id'));
    }

    public function testLeftJoinUsesOnConditionType(): void
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects(self::once())
            ->method('leftJoin')
            ->with('App\Entity\Order', 'o', Join::ON, 'o.book = book.id')
            ->willReturnSelf();

        $subject = $this->createJoinCriteria($queryBuilder);

        self::assertSame($subject, $subject->leftJoin('App\Entity\Order', 'o', 'o.book = book.id'));
    }

    private function createJoinCriteria(QueryBuilder $queryBuilder): object
    {
        return new class($queryBuilder) {
            use JoinCriteria;

            public function __construct(public QueryBuilder $qb)
            {
            }

            public function getAliasField(?string $customAlias, string $field): string
            {
                return ($customAlias ?? 'e').'.'.$field;
            }
        };
    }
}
