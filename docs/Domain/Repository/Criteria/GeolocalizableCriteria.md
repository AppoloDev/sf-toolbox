# GeolocalizableCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\GeolocalizableCriteria`
- Fichier source : `src/Domain/Repository/Criteria/GeolocalizableCriteria.php`

## Rôle

Trait **optionnel** apportant des filtres de recherche géographique (rayon, bounding box), à combiner avec [`BuilderCriteria`](BuilderCriteria.md) et [`WhereCriteria`](WhereCriteria.md) (utilise `between()`, fourni par `WhereCriteria`). Conçu pour fonctionner avec le concern d'entité [`Geolocalizable`](../../Entity/Concern/Geolocalizable.md) (champs `lat`/`lng`).

## API

### `around(float $lat, float $lng, int $radius = 10, ?string $customAlias = null, ?string $latField = 'lat', ?string $lngField = 'lng'): self`

Filtre les entités situées dans un rayon de `$radius` kilomètres autour du point `($lat, $lng)`, en utilisant la formule de Haversine (calcul de distance à vol d'oiseau) directement en SQL.

```php
// Livres disponibles à moins de 5 km de Paris :
$repository->getQB()->around(lat: 48.8566, lng: 2.3522, radius: 5)->getResults();
```

### `bounds(array $bounds, ?string $customAlias = null, ?string $latField = 'lat', ?string $lngField = 'lng'): self`

Filtre les entités dont les coordonnées tombent dans une "bounding box" (utile pour une recherche "dans la zone visible d'une carte"). `$bounds` attend les clés `slat`/`nlat` (latitude sud/nord) et/ou `slng`/`nlng` (longitude sud/nord) — chaque paire est optionnelle : si absente, aucun filtre n'est appliqué sur cet axe.

```php
$repository->getQB()->bounds([
    'slat' => 48.80, 'nlat' => 48.90,
    'slng' => 2.25,  'nlng' => 2.40,
])->getResults();
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Geolocalizable\Geolocalizable;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\GeolocalizableCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\WhereCriteria;

#[ORM\Entity(repositoryClass: VenueRepository::class)]
class Venue
{
    use Geolocalizable;
}

class VenueRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria, GeolocalizableCriteria, WhereCriteria; // WhereCriteria requis (between())

    protected static string $alias = 'venue';
    // ...
}

$nearbyVenues = $venueRepository->getQB()->around($userLat, $userLng, radius: 15)->getResults();
```

## Voir aussi

- [Domain/Entity/Concern/Geolocalizable](../../Entity/Concern/Geolocalizable.md) — le concern d'entité correspondant.
- [Form/FormType/GeolocalizableType](../../../Form/FormType/GeolocalizableType.md) — capture les coordonnées côté formulaire.
- [WhereCriteria](WhereCriteria.md) — fournit `between()`, utilisé en interne par `bounds()`.
