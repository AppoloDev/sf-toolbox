<?php

namespace AppoloDev\SFToolboxBundle\Utils;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

class UuidUtils
{
    public static function isUuid(mixed $value): bool
    {
        if ($value instanceof AbstractUid) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        try {
            Uuid::fromString($value);

            return true;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }
}
