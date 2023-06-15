<?php

namespace AppoloDev\SFToolboxBundle\Security\Http\Attribute;


use Attribute;
use Symfony\Component\ExpressionLanguage\Expression;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
final class IsNotGranted
{
    public function __construct(
        public string|Expression $attribute,
        /**
         * @var array<string|Expression>|string|Expression|null
         */
        public array|string|Expression|null $subject = null,
        public ?string $message = null,
        public ?int $statusCode = null,
        public ?int $exceptionCode = null,
    ) {
    }
}
