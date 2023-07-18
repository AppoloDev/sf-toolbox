<?php

namespace AppoloDev\SFToolboxBundle\Domain\Repository\Criteria;

// TODO: Refactor
trait GeolocalizableCriteria
{
    public function around(float $lat, float $lng, int $radius = 10, string $customAlias = null, ?string $latField = 'lat', ?string $lngField = 'lng'): self
    {
        // TODO: GeoLocalizableCriteria
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        $sqlDistance = "(6378 * acos(cos(radians({$lat})) * cos(radians({$alias}.{$latField})) * cos(radians({$alias}.{$lngField}) - radians({$lng})) + sin(radians({$lat})) * sin(radians({$alias}.{$latField}))))";

        $this->qb
            ->andWhere($this->qb->expr()->lt($sqlDistance, ':radius'))
            ->setParameter('radius', $radius);

        return $this;
    }

    public function bounds(array $bounds, string $customAlias = null, ?string $latField = 'lat', ?string $lngField = 'lng'): self
    {
        // TODO: GeoLocalizableCriteria
        $alias = !is_null($customAlias) ? $customAlias : self::$alias;

        if (isset($bounds['slat']) && isset($bounds['nlat'])) {
            $this->qb
                ->andWhere($this->qb->expr()->between($alias.'.'.$latField, ':fromLat', ':toLat'))
                ->setParameter('fromLat', (float) $bounds['slat'])
                ->setParameter('toLat', (float) $bounds['nlat']);
        }
        if (isset($bounds['slng']) && isset($bounds['nlng'])) {
            $this->qb
                ->andWhere($this->qb->expr()->between($alias.'.'.$lngField, ':fromLng', ':toLng'))
                ->setParameter('fromLng', (float) $bounds['slng'])
                ->setParameter('toLng', (float) $bounds['nlng']);
        }

        return $this;
    }
}