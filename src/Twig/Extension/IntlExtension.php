<?php

namespace AppoloDev\SFToolboxBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IntlExtension extends AbstractExtension
{
    public function __construct()
    {
        if (!class_exists(\IntlDateFormatter::class)) {
            throw new \RuntimeException('The native PHP intl extension (http://php.net/manual/en/book.intl.php) is needed to use intl-based filters.');
        }
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('localizeddate', [$this, 'localizeddate']),
        ];
    }

    public function localizeddate(
        \DateTime $date,
        string $format = null,
        string $locale = 'fr-FR',
        string $timeZone = 'Europe/Paris',
        bool $ucFirst = true
    ): string {
        $formatter = \IntlDateFormatter::create(
            $locale,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            $timeZone,
            \IntlDateFormatter::GREGORIAN,
            $format
        );

        $formattedDate = $formatter->format($date->getTimestamp());
        $formattedDate = is_string($formattedDate) ? $formattedDate : '';

        if (!$ucFirst) {
            return $formattedDate;
        }

        return implode(' ', array_map(fn ($item) => ucfirst($item), explode(' ', $formattedDate)));
    }
}
