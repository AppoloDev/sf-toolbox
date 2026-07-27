# GeolocalizableConstraint (+ GeolocalizableConstraintValidator)

- Classe (contrainte) : `AppoloDev\SFToolboxBundle\Form\Validator\Geolocalizable\GeolocalizableConstraint`
- Classe (validateur) : `AppoloDev\SFToolboxBundle\Form\Validator\Geolocalizable\GeolocalizableConstraintValidator`
- Fichiers source : `src/Form/Validator/Geolocalizable/GeolocalizableConstraint.php`, `GeolocalizableConstraintValidator.php`

## Rôle

Contrainte de validation Symfony vérifiant que la donnée JSON soumise par un champ [`GeolocalizableType`](../FormType/GeolocalizableType.md) contient bien certains champs obligatoires (ex: ne pas accepter une adresse sans code postal ni ville), **avant** même que [`GeolocalizableSubscriber`](../Subscriber/GeolocalizableSubscriber.md) ne les répercute sur l'entité.

## `GeolocalizableConstraint`

### `__construct(array $options)`

- `$options['requiredFields']` (**obligatoire** — voir `getRequiredOptions()`) : liste des clés attendues dans la donnée géocomplétée parsée (ex: `['city', 'zipCode']`).

### Propriétés publiques

- `string $message = 'This field is incomplete'` — message d'erreur par défaut (surchargeable via l'option standard `message` de toute contrainte Symfony).
- `array $requiredFields` — voir ci-dessus.

### `getRequiredOptions(): array`

Retourne `['requiredFields']` — Symfony Validator lèvera une erreur à la construction si cette option n'est pas fournie.

## `GeolocalizableConstraintValidator`

### `validate(mixed $value, Constraint $constraint): void`

Logique de validation, appelée automatiquement par le composant Validator (jamais directement) :
1. Si `$value` n'est pas une chaîne, ne valide rien (pas d'erreur ajoutée).
2. Parse `$value` via [`GeocompleteUtils::transformGeocompleteData()`](../../Utils/GeocompleteUtils.md).
3. Pour chaque champ de `$constraint->requiredFields`, vérifie sa présence (non `null`) dans la donnée parsée.
4. Si des champs manquent, ajoute une violation unique listant les champs manquants (`%fields%` dans le message, séparés par `, `).

## Exemple d'usage complet

Sur le champ du `FormType` :

```php
use AppoloDev\SFToolboxBundle\Form\FormType\GeolocalizableType;
use AppoloDev\SFToolboxBundle\Form\Validator\Geolocalizable\GeolocalizableConstraint;

$builder->add('location', GeolocalizableType::class, [
    'mappedFields' => ['formattedAddress', 'city', 'zipCode', 'lat', 'lng'],
    'requiredFields' => ['city', 'zipCode'], // pour l'affichage/logique JS
    'constraints' => [
        new GeolocalizableConstraint(['requiredFields' => ['city', 'zipCode']]),
    ],
]);
```

Ou directement sur une propriété d'entité, avec les attributs de validation Symfony :

```php
use AppoloDev\SFToolboxBundle\Form\Validator\Geolocalizable\GeolocalizableConstraint;

#[GeolocalizableConstraint(requiredFields: ['city', 'zipCode'])]
private string $rawGeolocationPayload;
```

## Voir aussi

- [Form/FormType/GeolocalizableType](../FormType/GeolocalizableType.md) — champ concerné par cette contrainte.
- [Utils/GeocompleteUtils](../../Utils/GeocompleteUtils.md) — parseur utilisé en interne.
