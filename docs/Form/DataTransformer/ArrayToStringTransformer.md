# ArrayToStringTransformer

- Classe : `AppoloDev\SFToolboxBundle\Form\DataTransformer\ArrayToStringTransformer`
- Fichier source : `src/Form/DataTransformer/ArrayToStringTransformer.php`
- Implémente : `Symfony\Component\Form\DataTransformerInterface`

## Rôle

Transformer de formulaire convertissant un `array` en `string` avec une logique de **valeurs par défaut prioritaires** — utile pour un champ où certaines valeurs "spéciales" doivent primer sur les autres si elles sont présentes dans le tableau (ex: un statut "aucun" qui doit s'afficher seul même si d'autres valeurs sont sélectionnées en interne).

## Constructeur

### `__construct(array $defaultValues, bool $multiple = false)`

- `$defaultValues` : liste de valeurs "prioritaires". Si l'une d'elles est présente dans le tableau à transformer, elle est retournée seule (voir `transform()`).
- `$multiple` : détermine le comportement de secours quand aucune valeur par défaut n'est trouvée (voir `transform()`).

## API

### `transform(mixed $value): ?string`

Sens **modèle → vue**. Retourne `null` si `$value` n'est pas un `array`. Sinon :
1. Si l'une des `$defaultValues` est présente dans `$value`, la retourne (la première trouvée, dans l'ordre de `$defaultValues`).
2. Sinon, si `$multiple` est `false` : retourne le **dernier** élément du tableau (`end($value)`).
3. Sinon (`$multiple: true`) : retourne tous les éléments joints par `,`.

```php
$transformer = new ArrayToStringTransformer(['none'], multiple: true);
$transformer->transform(['a', 'none', 'b']); // "none" (valeur par défaut prioritaire)
$transformer->transform(['a', 'b']);          // "a,b" (multiple: true, pas de défaut trouvé)
```

### `reverseTransform(mixed $value): array`

Sens **vue → modèle** : si `$value` est une chaîne, retourne `[$value]` (mode simple) ou `explode(',', $value)` (mode `$multiple`). Sinon retourne `[]`.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Form\DataTransformer\ArrayToStringTransformer;

// Un champ "visibilité" où choisir "privé" doit primer sur toute autre sélection :
$builder->get('visibility')->addModelTransformer(
    new ArrayToStringTransformer(['private'], multiple: true)
);
```

## Voir aussi

- [StringToArrayTransformer](StringToArrayTransformer.md) — version simple, sans logique de priorité.
