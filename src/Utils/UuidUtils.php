<?php

namespace AppoloDev\SFToolbox\Utils;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

class UuidUtils
{
    public static function isUuid(string $value): bool
    {
        try {
            Uuid::fromString($value);

            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
