# GeolocalizableSubscriber

- Classe : `AppoloDev\SFToolboxBundle\Form\Subscriber\GeolocalizableSubscriber`
- Fichier source : `src/Form/Subscriber/GeolocalizableSubscriber.php`
- Implémente : `Symfony\Component\EventDispatcher\EventSubscriberInterface`

## Rôle

Écouteur d'événements de formulaire, **enregistré automatiquement** (autowiring + autoconfiguration activés par défaut dans `config/services.yaml`, tag `kernel.event_subscriber`) — vous n'avez **rien à déclarer manuellement** pour qu'il fonctionne. Il s'exécute sur **tous les formulaires** de l'application (pas seulement ceux contenant un champ géolocalisé), mais ne fait rien s'il ne trouve aucun champ de type [`GeolocalizableType`](../FormType/GeolocalizableType.md).

Son rôle : lire le JSON de géocomplétion soumis via un champ `GeolocalizableType`, et répercuter automatiquement les valeurs extraites (adresse, ville, code postal, latitude/longitude...) sur les champs correspondants de l'entité liée au formulaire, tels que déclarés dans l'option `mappedFields` du champ.

## API

### `getSubscribedEvents(): array`

Déclare l'écoute de `FormEvents::PRE_SUBMIT` (→ `processGeolocalizableFieldsBeforeSubmit`) et `FormEvents::POST_SUBMIT` (→ `processGeolocalizableFieldsAfterSubmit`).

### `processGeolocalizableFieldsBeforeSubmit(PreSubmitEvent $event): void`

Appelé avant la soumission effective des données dans le formulaire. Parcourt tous les champs du formulaire, repère ceux dont le type interne est [`GeolocalizableType`](../FormType/GeolocalizableType.md), et pour chacun :
1. Extrait `mappedFields` depuis les options du champ.
2. Parse la valeur JSON soumise via [`GeocompleteUtils::transformGeocompleteData()`](../../Utils/GeocompleteUtils.md).
3. Stocke le résultat en mémoire (propriété interne `$geolocalizableFields`), pour le réutiliser dans `processGeolocalizableFieldsAfterSubmit()`.

Vous n'appelez jamais cette méthode vous-même — elle est déclenchée automatiquement par le composant Form.

### `processGeolocalizableFieldsAfterSubmit(PostSubmitEvent $event): void`

Appelé après la soumission. Pour chaque champ géolocalisé détecté à l'étape précédente, appelle le setter correspondant (`set<ChampMappé>`) sur l'objet du **formulaire parent** (l'entité liée au formulaire), pour chaque champ listé dans `mappedFields` dont la valeur est présente dans la donnée géocomplétée.

Ne fait rien si le setter n'existe pas sur l'entité (vérifié via `method_exists`), donc pas d'erreur si `mappedFields` contient un nom de champ que l'entité ne possède pas — mais aussi aucune valeur ne sera assignée dans ce cas, à surveiller si un mapping semble ne pas fonctionner.

## Exemple d'usage complet

Rien à faire explicitement à part configurer le champ `GeolocalizableType` avec `mappedFields` — l'assignation est automatique :

```php
use AppoloDev\SFToolboxBundle\Form\FormType\GeolocalizableType;

$builder->add('location', GeolocalizableType::class, [
    'mappedFields' => ['formattedAddress', 'address', 'city', 'zipCode', 'lat', 'lng'],
]);

// À la soumission :
// - $venue->setFormattedAddress(...)
// - $venue->setAddress(...)
// - $venue->setCity(...)
// - $venue->setZipCode(...)
// - $venue->setLat(...)
// - $venue->setLng(...)
// sont appelés automatiquement, sans code supplémentaire dans le contrôleur.
```

## Voir aussi

- [Form/FormType/GeolocalizableType](../FormType/GeolocalizableType.md) — le type de champ déclencheur.
- [Domain/Entity/Concern/Geolocalizable](../../Domain/Entity/Concern/Geolocalizable.md) — fournit les setters attendus.
- [Utils/GeocompleteUtils](../../Utils/GeocompleteUtils.md) — parseur du JSON de géocomplétion.
