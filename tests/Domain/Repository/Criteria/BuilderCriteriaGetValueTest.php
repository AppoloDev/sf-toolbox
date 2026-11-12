<?php

declare(strict_types=1);

namespace AppoloDev\SFToolboxBundle\Tests\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteria;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class BuilderCriteriaGetValueTest extends TestCase
{
    private object $subject;

    protected function setUp(): void
    {
        $this->subject = new class {
            use BuilderCriteria;
        };
    }

    public function testGetValueConvertsUuidObjectToBinary(): void
    {
        $uuid = Uuid::v7();

        self::assertSame($uuid->toBinary(), $this->subject->getValue($uuid));
    }

    public function testGetValueConvertsUuidStringToBinary(): void
    {
        $uuid = Uuid::v7();

        self::assertSame($uuid->toBinary(), $this->subject->getValue((string) $uuid));
    }

    public function testGetValueForUuidObjectMatchesGetValueForItsStringForm(): void
    {
        $uuid = Uuid::v7();

        self::assertSame(
            $this->subject->getValue((string) $uuid),
            $this->subject->getValue($uuid)
        );
    }

    public function testGetValueLeavesNonUuidValuesUnchanged(): void
    {
        self::assertSame('not-a-uuid', $this->subject->getValue('not-a-uuid'));
        self::assertSame(42, $this->subject->getValue(42));
        self::assertNull($this->subject->getValue(null));
    }
}
