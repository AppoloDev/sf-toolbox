<?php

namespace AppoloDev\SFToolboxBundle\Utils;

class ArrayUtils
{
    public static function recursiveFind(?array $haystack, string $needle): mixed
    {
        if (is_null($haystack)) {
            return null;
        }

        if (isset($haystack[$needle])) {
            return $haystack[$needle];
        }

        foreach ($haystack as $key => $value) {
            if ($key === $needle) {
                return $value;
            } else {
                if (is_array($value)) {
                    return self::recursiveFind($value, $needle);
                }
            }
        }

        return null;
    }
}
