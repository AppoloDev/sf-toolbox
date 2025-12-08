<?php

namespace AppoloDev\SFToolboxBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

readonly class StringToArrayTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return is_array($value) ? join(',', $value) : $value;
    }

    public function reverseTransform(mixed $value): ?array
    {
        if (!is_string($value)) {
            return null;
        }

        return explode(',', $value);
    }
}
