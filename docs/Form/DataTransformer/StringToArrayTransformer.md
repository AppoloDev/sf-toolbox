# StringToArrayTransformer

- Classe : `AppoloDev\SFToolboxBundle\Form\DataTransformer\StringToArrayTransformer`
- Fichier source : `src/Form/DataTransformer/StringToArrayTransformer.php`
- Implémente : `Symfony\Component\Form\DataTransformerInterface`

## Rôle

Transformer de formulaire convertissant un `array` (représentation "modèle", côté PHP/entité) en chaîne jointe par des virgules (représentation "vue", côté champ HTML), et inversement. Utilisé automatiquement par [TomSelectType](../FormType/TomSelectType.md) quand `multiple: true`.

## API

### `transform(mixed $value): mixed`

Sens **modèle → vue** : si `$value` est un `array`, le joint avec `,` (`implode`). Sinon retourne `$value` tel quel.

```php
$transformer->transform(['php', 'symfony']); // "php,symfony"
```

### `reverseTransform(mixed $value): ?array`

Sens **vue → modèle** : si `$value` est une chaîne, la découpe sur `,` (`explode`) et retourne le tableau obtenu. Sinon retourne `null`.

```php
$transformer->reverseTransform('php,symfony'); // ['php', 'symfony']
$transformer->reverseTransform(42); // null
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Form\DataTransformer\StringToArrayTransformer;

$builder->get('tags')->addModelTransformer(new StringToArrayTransformer());
```

C'est exactement ce que fait automatiquement [`TomSelectType`](../FormType/TomSelectType.md) en interne quand l'option `multiple` vaut `true` — vous n'avez pas besoin de l'ajouter vous-même dans ce cas précis.

## Voir aussi

- [ArrayToStringTransformer](ArrayToStringTransformer.md) — variante avec gestion de valeurs par défaut prioritaires.
- [FormType/TomSelectType](../FormType/TomSelectType.md) — utilisateur principal de ce transformer.
