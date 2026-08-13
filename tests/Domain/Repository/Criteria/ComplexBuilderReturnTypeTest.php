<?php

declare(strict_types=1);

namespace AppoloDev\SFToolboxBundle\Tests\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\ComplexBuilder;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ComplexBuilderReturnTypeTest extends TestCase
{
    #[DataProvider('comparisonMethodsProvider')]
    public function testComparisonMethodsReturnAComparisonNeverAFunc(string $method): void
    {
        $result = $this->createComplexBuilder()->{$method}('field', 'value');

        self::assertInstanceOf(Comparison::class, $result);
        self::assertNotInstanceOf(Func::class, $result);
    }

    #[DataProvider('inMethodsProvider')]
    public function testInMethodsReturnAFuncNeverAComparison(string $method): void
    {
        $result = $this->createComplexBuilder()->{$method}('field', ['a', 'b']);

        self::assertInstanceOf(Func::class, $result);
        self::assertNotInstanceOf(Comparison::class, $result);
    }

    public function testEqResultCanBePassedDirectlyToOrX(): void
    {
        $complexBuilder = $this->createComplexBuilder();

        $orX = $complexBuilder->orX(
            $complexBuilder->eq('resource', 'r-1'),
            $complexBuilder->eq('agent', 'a-1'),
        );

        self::assertNotNull($orX);
    }

    /**
     * @return list<list<string>>
     */
    public static function comparisonMethodsProvider(): array
    {
        return [['eq'], ['notEq'], ['gte'], ['gt'], ['lte'], ['lt']];
    }

    /**
     * @return list<list<string>>
     */
    public static function inMethodsProvider(): array
    {
        return [['in'], ['notIn']];
    }

    private function createComplexBuilder(): ComplexBuilder
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects(self::atLeastOnce())->method('expr')->willReturn(new Expr());

        $builderCriteria = $this->createMock(BuilderCriteriaInterface::class);
        $builderCriteria->expects(self::atLeastOnce())->method('getAliasField')
            ->willReturnCallback(fn (?string $customAlias, string $field): string => ($customAlias ?? 'e').'.'.$field);
        $builderCriteria->expects(self::atLeastOnce())->method('getQueryBuilder')->willReturn($queryBuilder);
        $builderCriteria->expects(self::atLeastOnce())->method('getValue')->willReturnCallback(static fn (mixed $value): mixed => $value);
        $builderCriteria->expects(self::atLeastOnce())->method('setParameter')->willReturn($builderCriteria);

        return new ComplexBuilder($builderCriteria);
    }
}
