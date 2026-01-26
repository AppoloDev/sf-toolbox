<?php

namespace AppoloDev\SFToolboxBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

readonly class ArrayToStringTransformer implements DataTransformerInterface
{
    public function __construct(private array $defaultValues, private bool $multiple = false)
    {
    }

    public function transform(mixed $value): ?string
    {
        if (!is_array($value)) {
            return null;
        }

        foreach ($this->defaultValues as $defaultValue) {
            if (in_array($defaultValue, $value)) {
                return $defaultValue;
            }
        }

        if (!$this->multiple) {
            $v = end($value);

            return is_string($v) ? $v : '';
        }

        return implode(',', $value);
    }

    public function reverseTransform(mixed $value): array
    {
        if (is_string($value)) {
            if (!$this->multiple) {
                return [$value];
            }

            return explode(',', $value);
        }

        return [];
    }
}
