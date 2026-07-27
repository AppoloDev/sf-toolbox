# GeolocalizableType

- Classe : `AppoloDev\SFToolboxBundle\Form\FormType\GeolocalizableType`
- Fichier source : `src/Form/FormType/GeolocalizableType.php`

## Rôle

Champ de formulaire caché (`HiddenType`), destiné à recevoir le JSON produit par un widget JavaScript de géocomplétion (type Google Places Autocomplete). Fonctionne de pair avec :
- [Domain/Entity/Concern/Geolocalizable](../../Domain/Entity/Concern/Geolocalizable.md) côté entité,
- [Form/Subscriber/GeolocalizableSubscriber](../Subscriber/GeolocalizableSubscriber.md), qui répercute automatiquement les données géocomplétées sur les champs de l'entité (`address`, `city`, `lat`, `lng`, etc.),
- [Form/Validator/GeolocalizableConstraint](../Validator/GeolocalizableConstraint.md), pour valider que certains champs de l'adresse sont bien renseignés.

## Options

| Option | Type | Défaut | Description |
|---|---|---|---|
| `placeholder` | `string` | `''` | Texte indicatif affiché dans le champ de recherche côté widget JS. |
| `mappedFields` | `array` | `[]` | Liste des champs de l'entité à renseigner automatiquement à partir de la donnée géocomplétée (ex: `['formattedAddress', 'city', 'lat', 'lng']`) — consommé par [`GeolocalizableSubscriber`](../Subscriber/GeolocalizableSubscriber.md). |
| `requiredFields` | `array` | `[]` | Liste des champs qui doivent obligatoirement être présents dans la donnée géocomplétée — consommé par [`GeolocalizableConstraint`](../Validator/GeolocalizableConstraint.md) si la contrainte est ajoutée. |
| `requestOptions` | `array` | `[]` | Options transmises telles quelles au widget JS (ex: restriction de pays, biais géographique). |
| `error_bubbling` | `bool` | `false` | Redéfini par rapport au défaut de `HiddenType`, pour que les erreurs de validation restent affichées sur ce champ plutôt que de remonter au formulaire parent. |

## Comportement

- `getParent()` retourne `HiddenType::class` : côté HTML, un simple `<input type="hidden">`.
- `getBlockPrefix()` retourne `'geo_localizable'`.
- Dans `buildView()`, transmet `placeholder` et `requestOptions` aux variables de vue Twig (`view.vars['placeholder']`, `view.vars['requestOptions']`), consommées par le template/JS du widget de géocomplétion.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Form\FormType\GeolocalizableType;

$builder->add('location', GeolocalizableType::class, [
    'placeholder' => 'Rechercher une adresse...',
    'mappedFields' => ['formattedAddress', 'address', 'city', 'zipCode', 'lat', 'lng'],
    'requiredFields' => ['city', 'zipCode'],
    'requestOptions' => ['componentRestrictions' => ['country' => 'fr']],
]);
```

Côté entité, il faut le concern [`Geolocalizable`](../../Domain/Entity/Concern/Geolocalizable.md) pour que les setters (`setFormattedAddress`, `setCity`, etc.) existent — c'est [`GeolocalizableSubscriber`](../Subscriber/GeolocalizableSubscriber.md) qui les appelle automatiquement à la soumission du formulaire, vous n'avez rien à faire de plus dans le contrôleur.

## Voir aussi

- [Domain/Entity/Concern/Geolocalizable](../../Domain/Entity/Concern/Geolocalizable.md)
- [Form/Subscriber/GeolocalizableSubscriber](../Subscriber/GeolocalizableSubscriber.md)
- [Form/Validator/GeolocalizableConstraint](../Validator/GeolocalizableConstraint.md)
- [Domain/Repository/Criteria/GeolocalizableCriteria](../../Domain/Repository/Criteria/GeolocalizableCriteria.md)
- [Utils/GeocompleteUtils](../../Utils/GeocompleteUtils.md)
