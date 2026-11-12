# UuidUtils

- Classe : `AppoloDev\SFToolboxBundle\Utils\UuidUtils`
- Fichier source : `src/Utils/UuidUtils.php`

## Rôle

Classe utilitaire (méthodes statiques uniquement) pour détecter si une chaîne est un UUID valide. Utilisée en interne par toute la couche [Domain/Repository/Criteria](../Domain/Repository/Criteria/BuilderCriteria.md) pour convertir automatiquement les identifiants UUID passés en chaîne vers le format binaire attendu par Doctrine.

## API

### `static isUuid(string $value): bool`

Retourne `true` si `$value` est une chaîne UUID syntaxiquement valide (tente `Uuid::fromString($value)`, retourne `false` en cas d'`\InvalidArgumentException`).

```php
use AppoloDev\SFToolboxBundle\Utils\UuidUtils;

UuidUtils::isUuid('0195e2d4-1234-7890-abcd-ef0123456789'); // true
UuidUtils::isUuid('pas-un-uuid'); // false
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Utils\UuidUtils;

if (UuidUtils::isUuid($request->query->get('id'))) {
    $book = $repository->getQB()->eq('id', $request->query->get('id'))->getSingleResult();
} else {
    throw new BadRequestException('Identifiant invalide');
}
```

## Voir aussi

- [Domain/Repository/Criteria/BuilderCriteria](../Domain/Repository/Criteria/BuilderCriteria.md) — `getValue()` s'appuie indirectement sur la même logique de détection.
- [Domain/Repository/Criteria/ComplexBuilder](../Domain/Repository/Criteria/ComplexBuilder.md) — détecte les UUID pour choisir le type de paramètre Doctrine à binder.
