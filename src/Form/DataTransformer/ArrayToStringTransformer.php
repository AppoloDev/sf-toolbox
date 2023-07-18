<?php

namespace AppoloDev\SFToolboxBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

readonly class ArrayToStringTransformer implements DataTransformerInterface
{
    public function __construct(private array $defaultValues)
    {
    }

    public function transform(mixed $value): ?string
    {
        foreach ($this->defaultValues as $defaultValue) {
            if (in_array($defaultValue, $value)) {
                return $defaultValue;
            }
        }

        return is_array($value) ? end($value) : null;
    }

    public function reverseTransform(mixed $value): array
    {
        if (is_string($value)) {
            return [$value];
        }

        return [];
    }
}