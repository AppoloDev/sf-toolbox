# Geolocalizable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Geolocalizable\Geolocalizable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Geolocalizable\GeolocalizableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Geolocalizable/Geolocalizable.php`, `GeolocalizableInterface.php`

## Rôle

Ajoute les champs d'une adresse géolocalisée (adresse, code postal, ville, latitude/longitude, adresse formatée) à une entité. Conçu pour fonctionner avec :
- [Form/FormType/GeolocalizableType](../../../Form/FormType/GeolocalizableType.md) côté formulaire (champ caché + widget JS de geocomplétion),
- [Domain/Repository/Criteria/GeolocalizableCriteria](../../Repository/Criteria/GeolocalizableCriteria.md) côté repository (recherche par rayon/bounding box).

## Propriétés mappées

```php
#[ORM\Column(type: Types::TEXT, nullable: true)]
private ?string $address = null;

#[ORM\Column(type: Types::STRING, length: 5, nullable: true)]
private ?string $zipCode = null;

#[ORM\Column(type: Types::STRING, nullable: true)]
private ?string $city = null;

#[ORM\Column(type: Types::FLOAT, nullable: true)]
private ?float $lat = null;

#[ORM\Column(type: Types::FLOAT, nullable: true)]
private ?float $lng = null;

#[ORM\Column(type: Types::STRING, nullable: true)]
private ?string $formattedAddress = null;
```

Groupe de sérialisation : `localisation`.

## API

### `getAddress(): ?string` / `setAddress(?string $address): self`

Adresse brute (ligne de rue).

### `getZipCode(): ?string` / `setZipCode(?string $zipCode): self`

Code postal (5 caractères max).

### `getCity(): ?string` / `setCity(?string $city): self`

Ville.

### `getLat(): ?float` / `setLat(?float $lat): self`

Latitude.

### `getLng(): ?float` / `setLng(?float $lng): self`

Longitude.

### `getFormattedAddress(): ?string` / `setFormattedAddress(?string $formattedAddress): self`

Adresse complète, formatée telle que renvoyée par le service de géocomplétion (Google Places ou équivalent) — c'est généralement cette valeur qui est affichée à l'utilisateur.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Geolocalizable\Geolocalizable;

#[ORM\Entity(repositoryClass: VenueRepository::class)]
class Venue
{
    use Geolocalizable;
}

$venue = new Venue();
$venue
    ->setFormattedAddress('10 Rue de Rivoli, 75004 Paris, France')
    ->setAddress('10 Rue de Rivoli')
    ->setZipCode('75004')
    ->setCity('Paris')
    ->setLat(48.8556)
    ->setLng(2.3522);
```

En pratique, ces champs sont le plus souvent remplis automatiquement par [GeolocalizableSubscriber](../../../Form/Subscriber/GeolocalizableSubscriber.md) lors de la soumission d'un formulaire contenant un champ [GeolocalizableType](../../../Form/FormType/GeolocalizableType.md), plutôt qu'assignés à la main.

## Voir aussi

- [Form/FormType/GeolocalizableType](../../../Form/FormType/GeolocalizableType.md)
- [Form/Subscriber/GeolocalizableSubscriber](../../../Form/Subscriber/GeolocalizableSubscriber.md)
- [Form/Validator/GeolocalizableConstraint](../../../Form/Validator/GeolocalizableConstraint.md)
- [Domain/Repository/Criteria/GeolocalizableCriteria](../../Repository/Criteria/GeolocalizableCriteria.md)
- [Utils/GeocompleteUtils](../../../Utils/GeocompleteUtils.md)
