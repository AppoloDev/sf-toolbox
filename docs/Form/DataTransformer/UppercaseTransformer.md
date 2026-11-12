# UppercaseTransformer

- Classe : `AppoloDev\SFToolboxBundle\Form\DataTransformer\UppercaseTransformer`
- Fichier source : `src/Form/DataTransformer/UppercaseTransformer.php`
- Implémente : `Symfony\Component\Form\DataTransformerInterface`

## Rôle

Transformer de formulaire qui met en majuscules la saisie utilisateur **à la soumission**, sans modifier l'affichage initial.

> ⚠️ **Piège** : la mise en majuscule se fait dans `reverseTransform()` (vue → modèle), donc **à la soumission du formulaire**, pas dans `transform()` (modèle → vue, à l'affichage). Si vous vous attendiez à voir la valeur affichée en majuscules avant même la soumission, ce n'est pas ce que fait ce transformer.

## API

### `transform(mixed $value): ?string`

Sens **modèle → vue** : retourne `$value` inchangé s'il s'agit d'une chaîne, sinon `null`. Aucune mise en majuscule ici.

### `reverseTransform(mixed $value): ?string`

Sens **vue → modèle** : retourne `strtoupper($value)` si `$value` est une chaîne, sinon `null`. C'est ici que la conversion en majuscules a lieu, au moment où l'utilisateur soumet le formulaire.

```php
$transformer->reverseTransform('abc123'); // "ABC123"
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Form\DataTransformer\UppercaseTransformer;

$builder->get('reference')->addModelTransformer(new UppercaseTransformer());
// L'utilisateur tape "ref-2026", l'entité reçoit "REF-2026" après soumission.
```
