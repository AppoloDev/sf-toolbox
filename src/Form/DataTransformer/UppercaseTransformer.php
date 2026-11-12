<?php

namespace AppoloDev\SFToolboxBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

readonly class UppercaseTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }

    public function reverseTransform(mixed $value): ?string
    {
        return is_string($value) ? strtoupper($value) : null;
    }
}
