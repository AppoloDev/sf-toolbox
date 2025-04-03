<?php

namespace AppoloDev\SFToolboxBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

readonly class StringToArrayTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return is_array($value) ? join(',', $value) : $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (!$value) {
            return null;
        }

        return explode(',', $value);
    }
}
