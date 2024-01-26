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
            } elseif (is_array($value)) {
                return self::recursiveFind($value, $needle);
            }
        }

        return null;
    }

    private function flatten(array $array): array
    {
        $result = [];
        foreach ($array as $item) {
            if (\is_array($item)) {
                $result = array_merge($result, $this->flatten($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }
}
