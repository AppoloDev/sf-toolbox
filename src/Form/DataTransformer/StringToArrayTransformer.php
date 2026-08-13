<?php

namespace AppoloDev\SFToolboxBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

readonly class StringToArrayTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return is_array($value) ? implode(',', array_map(self::stringifyArrayValue(...), $value)) : $value;
    }

    public function reverseTransform(mixed $value): ?array
    {
        if (!is_string($value)) {
            return null;
        }

        return explode(',', $value);
    }

    private static function stringifyArrayValue(mixed $value): string
    {
        if (is_scalar($value)) {
            return (string) $value;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return '';
    }
}
