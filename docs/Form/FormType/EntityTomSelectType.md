# EntityTomSelectType

- Classe : `AppoloDev\SFToolboxBundle\Form\FormType\EntityTomSelectType`
- Fichier source : `src/Form/FormType/EntityTomSelectType.php`

## Rôle

Variante d'`EntityType` (le type Doctrine natif de Symfony) enrichie pour être pilotée par le widget JavaScript [Tom Select](https://tom-select.js.org/), de la même façon que [TomSelectType](TomSelectType.md), mais pour un choix **basé sur des entités** plutôt que sur des chaînes libres.

## Options

Hérite de **toutes** les options natives d'`EntityType` (`class`, `query_builder`, `choice_label`, `multiple`, etc. — voir la [documentation Symfony EntityType](https://symfony.com/doc/current/reference/forms/types/entity.html)), plus :

| Option | Type | Défaut | Description |
|---|---|---|---|
| `configuration` | `array` | `[]` | Options passées telles quelles au widget JS, fusionnées avec `maxItems` calculé automatiquement (voir ci-dessous). |
| `multiple` | `bool` | `false` | Redéclarée ici uniquement pour forcer le typage `bool` strict (le comportement natif d'`EntityType` reste inchangé). |

## Comportement

- `getBlockPrefix()` retourne `'tom_select'` (même thème Twig que [TomSelectType](TomSelectType.md)).
- Dans `buildView()`, `configuration['maxItems']` est calculé automatiquement si non fourni : `1` si `multiple: false`, `null` (illimité) si `multiple: true`.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Form\FormType\EntityTomSelectType;

$builder->add('author', EntityTomSelectType::class, [
    'class' => Author::class,
    'choice_label' => 'fullName',
    'multiple' => false,
]);

$builder->add('categories', EntityTomSelectType::class, [
    'class' => Category::class,
    'choice_label' => 'name',
    'multiple' => true,
    'configuration' => ['create' => false],
]);
```

## Voir aussi

- [TomSelectType](TomSelectType.md) — équivalent basé sur des chaînes libres plutôt que des entités.
