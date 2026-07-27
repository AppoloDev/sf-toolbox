# WhereCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\WhereCriteria`
- Fichier source : `src/Domain/Repository/Criteria/WhereCriteria.php`

## Rôle

Trait apportant des filtres `WHERE` simples et chainables, à combiner avec [`BuilderCriteria`](BuilderCriteria.md). Chaque méthode ne gère **qu'une seule condition à la fois** (contrairement à [`ComplexBuilder`](ComplexBuilder.md), utilisable directement dans `complexQuery()` pour combiner plusieurs conditions avec `andX`/`orX`). En interne, chaque méthode délègue à `complexQuery()` + `ComplexBuilder`.

## API

Toutes les méthodes retournent `self` et sont chainables.

### `in(string $field, array|string $params, ?string $customAlias = null): self`

`WHERE champ IN (...)`.

```php
$repository->getQB()->in('status', ['draft', 'pending'])->getResults();
```

### `notIn(string $field, array|string $params, ?string $customAlias = null): self`

`WHERE champ NOT IN (...)`.

### `isNull(string $field, ?string $customAlias = null): self`

`WHERE champ IS NULL`.

### `isNotNull(string $field, ?string $customAlias = null): self`

`WHERE champ IS NOT NULL`.

### `eq(string $field, int|bool|string|\DateTimeInterface|null $value, ?string $customAlias = null): self`

`WHERE champ = valeur`. Détecte automatiquement les chaînes UUID et les convertit au format binaire attendu par Doctrine.

```php
$repository->getQB()->eq('enabled', true)->getResults();
$repository->getQB()->eq('author', $authorUuidString)->getResults(); // UUID géré automatiquement
```

### `notEq(string $field, ..., ?string $customAlias = null): self`

`WHERE champ != valeur`.

### `gt(string $field, ..., ?string $customAlias = null): self`

`WHERE champ > valeur`.

### `gte(string $field, ..., ?string $customAlias = null): self`

`WHERE champ >= valeur`.

### `lt(string $field, ..., ?string $customAlias = null): self`

`WHERE champ < valeur`.

### `lte(string $field, ..., ?string $customAlias = null): self`

`WHERE champ <= valeur`.

### `between(string $field, string|\DateTimeInterface|null $from, string|\DateTimeInterface|null $to, ?string $customAlias = null): self`

`WHERE champ BETWEEN from AND to`.

```php
$repository->getQB()->between('price', 10, 50)->getResults();
```

### `searchIntoFields(?string $query, array $fields, ?string $customAlias = null): self`

Recherche "plein texte" basique sur plusieurs champs (voir [ComplexBuilder::searchIntoFields()](ComplexBuilder.md) pour le détail de l'algorithme).

```php
$repository->getQB()->searchIntoFields($request->query->get('q'), ['title', 'description'])->getResults();
```

### `whereExpr(ExpressionInterface $selectExpression): self`

Pose une clause `WHERE` brute à partir d'une [Expression](Expression/Expression.md) (écrase toute clause `WHERE` précédente — utilise `$qb->where()`).

### `orWhereExpr(ExpressionInterface $selectExpression): self`

Ajoute une clause `OR WHERE` à partir d'une [Expression](Expression/Expression.md).

### `andWhereExpr(ExpressionInterface $selectExpression): self`

Ajoute une clause `AND WHERE` à partir d'une [Expression](Expression/Expression.md).

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\StringExpr;

$repository->getQB()->andWhereExpr(new StringExpr('book.publishedYear >= 2020'))->getResults();
```

## Exemple d'usage complet

```php
$books = $bookRepository
    ->getQB()
    ->eq('enabled', true)
    ->notIn('status', ['archived', 'deleted'])
    ->searchIntoFields($queryString, ['title', 'isbn'])
    ->between('price', 0, 100)
    ->getResults();
```

## Voir aussi

- [ComplexBuilder](ComplexBuilder.md) — pour combiner plusieurs conditions dans une même clause (`andX`/`orX`), impossible avec `WhereCriteria` seul.
- [Expression/Expression](Expression/Expression.md) — pour injecter des fragments DQL bruts.
