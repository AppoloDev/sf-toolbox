<?php

namespace AppoloDev\SFToolboxBundle\Utils;

class ArrayUtils
{
    public static function recursiveFind(?array $haystack, string $needle): mixed
    {
        if (is_null($haystack)) {
            return null;
        }

        if (array_key_exists($needle, $haystack)) {
            return $haystack[$needle];
        }

        foreach ($haystack as $key => $value) {
            if ($key === $needle) {
                return $value;
            } else if (is_array($value)) {
                return self::recursiveFind($value, $needle);
            }
        }

        return null;
    }
}
