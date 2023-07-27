<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\StringExpr;

trait GeolocalizableCriteria
{
    public function around(
        float $lat,
        float $lng,
        int $radius = 10,
        string $customAlias = null,
        ?string $latField = 'lat',
        ?string $lngField = 'lng'
    ): self {
        $latField = $this->getAliasField($customAlias, $latField);
        $lngField = $this->getAliasField($customAlias, $lngField);

        $sqlDistance = "(6378 * acos(cos(radians({$lat})) * cos(radians({$latField})) * cos(radians({$lngField}) - radians({$lng})) + sin(radians({$lat})) * sin(radians({$latField}))))";

        return $this->andWhereExpr(new StringExpr($sqlDistance.' <= :radius'))->setParameter('radius', $radius);
    }

    public function bounds(
        array $bounds,
        string $customAlias = null,
        ?string $latField = 'lat',
        ?string $lngField = 'lng'
    ): self {
        if (isset($bounds['slat']) && isset($bounds['nlat'])) {
            $this->between($latField, $bounds['slat'], $bounds['nlat'], $customAlias);
        }
        if (isset($bounds['slng']) && isset($bounds['nlng'])) {
            $this->between($lngField, $bounds['slng'], $bounds['nlng'], $customAlias);
        }

        return $this;
    }
}
