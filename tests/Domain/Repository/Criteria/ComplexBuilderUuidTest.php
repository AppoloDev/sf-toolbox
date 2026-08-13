<?php

declare(strict_types=1);

namespace AppoloDev\SFToolboxBundle\Tests\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\ComplexBuilder;
use AppoloDev\SFToolboxBundle\Utils\UuidUtils;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

class ComplexBuilderUuidTest extends TestCase
{
    public function testEqWithUuidObjectProducesSameParamAsItsStringForm(): void
    {
        $uuid = Uuid::v7();

        [$builderCriteriaForObject, $capturedForObject] = $this->createBuilderCriteria();
        (new ComplexBuilder($builderCriteriaForObject))->eq('company', $uuid);

        [$builderCriteriaForString, $capturedForString] = $this->createBuilderCriteria();
        (new ComplexBuilder($builderCriteriaForString))->eq('company', (string) $uuid);

        self::assertSame('uuid', $capturedForObject->type);
        self::assertSame($uuid->toBinary(), $capturedForObject->value);
        self::assertSame($capturedForString->type, $capturedForObject->type);
        self::assertSame($capturedForString->value, $capturedForObject->value);
    }

    public function testInWithArrayContainingUuidObjectsMapsEachToBinary(): void
    {
        $uuid1 = Uuid::v7();
        $uuid2 = Uuid::v7();

        [$builderCriteria, $captured] = $this->createBuilderCriteria();
        (new ComplexBuilder($builderCriteria))->in('company', [$uuid1, (string) $uuid2, 'not-a-uuid']);

        self::assertSame([
            $uuid1->toBinary(),
            $uuid2->toBinary(),
            'not-a-uuid',
        ], $captured->value);
    }

    public function testNotInWithArrayContainingUuidObjectsMapsEachToBinary(): void
    {
        $uuid1 = Uuid::v7();
        $uuid2 = Uuid::v7();

        [$builderCriteria, $captured] = $this->createBuilderCriteria();
        (new ComplexBuilder($builderCriteria))->notIn('company', [$uuid1, $uuid2]);

        self::assertSame([$uuid1->toBinary(), $uuid2->toBinary()], $captured->value);
    }

    /**
     * @return array{0: BuilderCriteriaInterface&MockObject, 1: \stdClass}
     */
    private function createBuilderCriteria(): array
    {
        $captured = new \stdClass();
        $captured->value = null;
        $captured->type = null;

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects(self::once())->method('expr')->willReturn(new Expr());

        $builderCriteria = $this->createMock(BuilderCriteriaInterface::class);
        $builderCriteria->method('getAliasField')
            ->willReturnCallback(fn (?string $customAlias, string $field): string => ($customAlias ?? 'e').'.'.$field);
        $builderCriteria->method('getQueryBuilder')->willReturn($queryBuilder);
        $builderCriteria->method('getValue')->willReturnCallback(
            static function (mixed $value): mixed {
                if ($value instanceof AbstractUid) {
                    return $value->toBinary();
                }

                return is_string($value) && UuidUtils::isUuid($value) ? Uuid::fromString($value)->toBinary() : $value;
            }
        );
        $builderCriteria->expects(self::once())->method('setParameter')
            ->willReturnCallback(function (string $paramName, mixed $value, ?string $type = null) use ($captured, $builderCriteria) {
                $captured->value = $value;
                $captured->type = $type;

                return $builderCriteria;
            });

        return [$builderCriteria, $captured];
    }
}
