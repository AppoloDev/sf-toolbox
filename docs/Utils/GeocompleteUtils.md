# GeocompleteUtils

- Classe : `AppoloDev\SFToolboxBundle\Utils\GeocompleteUtils`
- Fichier source : `src/Utils/GeocompleteUtils.php`

## Rôle

Classe utilitaire (méthode statique unique) qui parse le JSON produit par un widget de géocomplétion façon Google Places Autocomplete, et le transforme en tableau associatif simple, directement exploitable pour renseigner les champs du concern [`Geolocalizable`](../Domain/Entity/Concern/Geolocalizable.md). Utilisé en interne par [Form/Subscriber/GeolocalizableSubscriber](../Form/Subscriber/GeolocalizableSubscriber.md) et [Form/Validator/GeolocalizableConstraint](../Form/Validator/GeolocalizableConstraint.md).

## API

### `static transformGeocompleteData(string $geocompleteJson): array`

Parse `$geocompleteJson` (attend la forme `{ formattedAddress, location: { lat, lng }, addressComponents: [{ types: [], longText }] }`, typique de l'API Google Places) et retourne un tableau avec les clés :

- `formattedAddress` (`string`, `''` si absent)
- `lat` (`float|int`, `0` si absent)
- `lng` (`float|int`, `0` si absent)
- `city` — présent uniquement si un composant d'adresse a le type `locality`
- `zipCode` — présent uniquement si un composant d'adresse a le type `postal_code`
- `country` — présent uniquement si un composant d'adresse a le type `country`

Retourne un tableau vide si `$geocompleteJson` n'est pas un JSON valide (`json_decode` retourne `null`).

```php
use AppoloDev\SFToolboxBundle\Utils\GeocompleteUtils;

$json = '{
    "formattedAddress": "10 Rue de Rivoli, 75004 Paris, France",
    "location": {"lat": 48.8556, "lng": 2.3522},
    "addressComponents": [
        {"types": ["locality"], "longText": "Paris"},
        {"types": ["postal_code"], "longText": "75004"},
        {"types": ["country"], "longText": "France"}
    ]
}';

GeocompleteUtils::transformGeocompleteData($json);
// [
//     'formattedAddress' => '10 Rue de Rivoli, 75004 Paris, France',
//     'lat' => 48.8556,
//     'lng' => 2.3522,
//     'city' => 'Paris',
//     'zipCode' => '75004',
//     'country' => 'France',
// ]
```

> ⚠️ Le widget JS doit produire un JSON respectant **exactement** cette forme (`addressComponents[].types`/`longText`, `location.lat`/`lng`) — un format différent (ex: l'ancienne API Google Places `address_components`/`long_name`) ne sera pas reconnu correctement.

## Voir aussi

- [Domain/Entity/Concern/Geolocalizable](../Domain/Entity/Concern/Geolocalizable.md) — champs alimentés par ce parseur.
- [Form/Subscriber/GeolocalizableSubscriber](../Form/Subscriber/GeolocalizableSubscriber.md) — utilisateur principal.
- [Form/Validator/GeolocalizableConstraint](../Form/Validator/GeolocalizableConstraint.md) — utilise aussi ce parseur pour valider les champs requis.
