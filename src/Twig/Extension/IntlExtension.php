<?php

namespace AppoloDev\SFToolboxBundle\Twig\Extension;

use DateTime;
use IntlDateFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IntlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('localizedDate', [$this, 'localizedDate']),
        ];
    }

    public function localizedDate(DateTime $date, string $format = null, string $locale = 'fr-FR'): string
    {
        $formattedDate = IntlDateFormatter::create(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
            $format
        )->format($date->getTimestamp());

        return (!is_string($formattedDate)) ? '' : ucwords($formattedDate);
    }
}
