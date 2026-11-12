# ArrayUtils

- Classe : `AppoloDev\SFToolboxBundle\Utils\ArrayUtils`
- Fichier source : `src/Utils/ArrayUtils.php`

## Rôle

Classe utilitaire (méthodes statiques uniquement) pour manipuler des tableaux imbriqués.

## API

### `static recursiveFind(?array $haystack, string $needle): mixed`

Cherche récursivement une clé `$needle` dans `$haystack` (à n'importe quelle profondeur) et retourne la **première** valeur trouvée, ou `null` si absente (ou si `$haystack` est `null`).

```php
use AppoloDev\SFToolboxBundle\Utils\ArrayUtils;

$data = ['user' => ['address' => ['zipCode' => '75001']]];
ArrayUtils::recursiveFind($data, 'zipCode'); // "75001"
ArrayUtils::recursiveFind($data, 'inconnu'); // null
```

> Notez que la recherche s'arrête à la **première** correspondance trouvée en parcourant les clés du tableau dans l'ordre — s'il existe plusieurs clés `$needle` à des niveaux différents, seule la première rencontrée est retournée.

### `static flatten(array $array): array`

Aplati récursivement un tableau imbriqué en une liste plate de valeurs scalaires (perd les clés).

```php
use AppoloDev\SFToolboxBundle\Utils\ArrayUtils;

ArrayUtils::flatten([1, [2, 3, [4, 5]], 6]); // [1, 2, 3, 4, 5, 6]
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Utils\ArrayUtils;

$config = [
    'app' => [
        'mailer' => ['sender' => 'contact@site.fr'],
    ],
];

$sender = ArrayUtils::recursiveFind($config, 'sender'); // "contact@site.fr"
```
