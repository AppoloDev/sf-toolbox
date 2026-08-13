<?php

declare(strict_types=1);

namespace AppoloDev\SFToolboxBundle\Tests\Utils;

use AppoloDev\SFToolboxBundle\Utils\UuidUtils;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UuidUtilsTest extends TestCase
{
    public function testIsUuidReturnsTrueForValidUuidString(): void
    {
        self::assertTrue(UuidUtils::isUuid((string) Uuid::v7()));
    }

    public function testIsUuidReturnsFalseForInvalidString(): void
    {
        self::assertFalse(UuidUtils::isUuid('not-a-uuid'));
    }

    public function testIsUuidReturnsTrueForUuidObject(): void
    {
        self::assertTrue(UuidUtils::isUuid(Uuid::v7()));
    }

    public function testIsUuidReturnsFalseForNonStringNonUidScalars(): void
    {
        self::assertFalse(UuidUtils::isUuid(42));
        self::assertFalse(UuidUtils::isUuid(true));
        self::assertFalse(UuidUtils::isUuid(null));
    }
}
