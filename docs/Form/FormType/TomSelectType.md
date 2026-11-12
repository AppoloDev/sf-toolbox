# TomSelectType

- Classe : `AppoloDev\SFToolboxBundle\Form\FormType\TomSelectType`
- Fichier source : `src/Form/FormType/TomSelectType.php`

## Rôle

Type de champ de formulaire basé sur `TextType`, destiné à être amélioré côté client par le widget JavaScript [Tom Select](https://tom-select.js.org/) (choix multiple, recherche, tags libres...). Le bundle ne fournit que la partie serveur (le type de champ + les variables de vue) ; le contrôleur Stimulus/JS branchant réellement Tom Select doit exister dans les assets du projet consommateur.

Pour un champ basé sur des entités Doctrine plutôt que sur des chaînes libres, voir [EntityTomSelectType](EntityTomSelectType.md).

## Options

| Option | Type | Défaut | Description |
|---|---|---|---|
| `configuration` | `array` | `[]` | Options passées telles quelles au widget JS (transmises via `view.vars['configuration']`), fusionnées avec `maxItems` et `options` (voir ci-dessous). |
| `multiple` | `bool` | `false` | Autorise la sélection/saisie de plusieurs valeurs. |
| `choices` | `array` | `[]` | Liste de choix proposés (transmise au widget JS via `configuration['options']`) — **n'est pas un vrai `ChoiceType`**, c'est juste une donnée passée à Tom Select côté client. |

## Comportement

- `getParent()` retourne `TextType::class` : le champ reste, côté serveur, un simple champ texte.
- `getBlockPrefix()` retourne `'tom_select'` (nom du bloc Twig à thémer côté projet).
- Dans `buildForm()`, si `multiple: true`, un [`StringToArrayTransformer`](../DataTransformer/StringToArrayTransformer.md) est ajouté automatiquement comme *model transformer* : la valeur manipulée en PHP est un `array`, mais elle est transmise/reçue comme une chaîne jointe par des virgules côté formulaire (format attendu par Tom Select en mode multiple).
- Dans `buildView()`, `configuration['maxItems']` est calculé automatiquement si non fourni : `1` si `multiple: false`, `null` (illimité) si `multiple: true`. `configuration['options']` reçoit toujours la valeur de `choices`.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Form\FormType\TomSelectType;

$builder->add('tags', TomSelectType::class, [
    'multiple' => true,
    'choices' => ['php', 'symfony', 'doctrine'],
    'configuration' => [
        'create' => true, // autorise la création de nouveaux tags à la volée (option Tom Select)
    ],
]);
```

Côté entité, le champ `tags` doit être un `array` (le `StringToArrayTransformer` fait la conversion vers/depuis la chaîne attendue par le widget) :

```php
#[ORM\Column(type: 'json')]
private array $tags = [];
```

## Voir aussi

- [EntityTomSelectType](EntityTomSelectType.md) — équivalent basé sur des entités Doctrine.
- [DataTransformer/StringToArrayTransformer](../DataTransformer/StringToArrayTransformer.md) — transformer utilisé automatiquement en mode `multiple`.
