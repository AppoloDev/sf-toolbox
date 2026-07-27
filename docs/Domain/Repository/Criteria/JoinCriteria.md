# JoinCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\JoinCriteria`
- Fichier source : `src/Domain/Repository/Criteria/JoinCriteria.php`

## Rôle

Trait apportant des jointures (`JOIN`/`LEFT JOIN`) chainables, à combiner avec [`BuilderCriteria`](BuilderCriteria.md).

## API

### `with(string $field, string $joinAlias, ?string $customAlias = null, ?bool $addSelect = false): self`

`LEFT JOIN` sur un champ d'association de l'entité courante (relation Doctrine, ex: `book.author`). Si `$addSelect` vaut `true`, ajoute aussi l'alias joint au `SELECT` (`addSelect($joinAlias)`), ce qui évite le problème classique du **N+1** en hydratant l'association jointe en une seule requête.

```php
$repository->getQB()
    ->with('author', 'a', addSelect: true) // LEFT JOIN book.author a, ajouté au SELECT
    ->getResults();
```

### `join(string $className, string $joinAlias, string $conditions): self`

`JOIN` sur une classe arbitraire (pas nécessairement une association mappée sur l'entité courante), avec une condition `WITH` explicite.

```php
$repository->getQB()->join(Order::class, 'o', 'o.book = book.id')->getResults();
```

### `leftJoin(string $className, string $joinAlias, string $conditions): self`

Identique à `join()` mais en `LEFT JOIN` (les entités sans correspondance sont quand même incluses, avec des valeurs `null` pour l'alias joint).

```php
$repository->getQB()->leftJoin(Order::class, 'o', 'o.book = book.id')->getResults();
```

## Exemple d'usage complet

```php
class BookRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria, JoinCriteria, WhereCriteria;

    protected static string $alias = 'book';
    // ...
}

$books = $bookRepository
    ->getQB()
    ->with('author', 'a', addSelect: true)
    ->eq('enabled', true, customAlias: 'book')
    ->getResults();
```

## Voir aussi

- [SelectCriteria](SelectCriteria.md) — pour sélectionner des champs spécifiques sur l'alias joint.
- [BuilderCriteria](BuilderCriteria.md) — le socle nécessaire à ce trait.
