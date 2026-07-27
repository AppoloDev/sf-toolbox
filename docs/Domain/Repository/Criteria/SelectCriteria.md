# SelectCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\SelectCriteria`
- Fichier source : `src/Domain/Repository/Criteria/SelectCriteria.php`

## Rôle

Trait apportant des méthodes de `SELECT` (projections, agrégations, sous-requêtes), à combiner avec [`BuilderCriteria`](BuilderCriteria.md).

## API

### `select(string $field, ?string $customAlias = null): self`

Remplace le `SELECT` par un champ unique.

```php
$repository->getQB()->select('title')->getArrayResults();
```

### `addSelect(string $field, ?string $customAlias = null): self`

Ajoute un champ au `SELECT` existant (sans écraser les précédents).

### `selectDistinct(string $field, ?string $customAlias = null): self`

`SELECT DISTINCT champ`.

```php
$categories = $repository->getQB()->selectDistinct('category')->getArrayResults();
```

### `max(string $field, ?string $customAlias = null, bool $addSelect = false): self`

`SELECT MAX(champ)`. Voir `getSingleScalarResult()` (dans [BuilderCriteria](BuilderCriteria.md)) pour récupérer la valeur.

### `min(string $field, ?string $customAlias = null, bool $addSelect = false): self`

`SELECT MIN(champ)`.

### `sum(string $field, ?string $customAlias = null, bool $addSelect = false): self`

`SELECT SUM(champ)`.

### `countItem(string $field, ?string $customAlias = null, bool $addSelect = false): self`

`SELECT COUNT(champ)`. S'appelle `countItem` (et non `count`) pour éviter toute confusion avec l'interface `Countable`/la fonction native `count()`.

```php
$total = $bookRepository->getQB()->eq('enabled', true)->countItem('id')->getSingleScalarResult();
```

### `selectFromFunction(string $function, string $field, ?string $customAlias = null, bool $addSelect = false): self`

Méthode générique sur laquelle `max`/`min`/`sum`/`countItem` sont construites (`SELECT <FUNCTION>(champ)`). Le paramètre `$addSelect` détermine si la fonction remplace le `SELECT` (`false`, par défaut) ou s'ajoute à un `SELECT` existant (`true`).

### `selectExpr(ExpressionInterface $selectExpression): self`

Construit le `SELECT` à partir d'une [Expression](Expression/Expression.md) (`ExpressionInterface`) — utile pour des projections DQL personnalisées, comme [`PartialExpr`](Expression/Select.md) ou [`PartialDtoExpr`](Expression/Select.md).

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\Select\PartialDtoExpr;

$repository->getQB()->selectExpr(
    new PartialDtoExpr(['id', 'title'], BookSummaryDto::class)
)->getResults(); // hydrate directement des BookSummaryDto
```

### `selectFromSubQuery(string $entityClass, string $alias, callable $cb, ?string $subSelectAlias = null): self`

Injecte une sous-requête corrélée dans le `SELECT`. `$cb` reçoit un repository de `$entityClass` déjà positionné en sous-requête (via [`BuilderCriteria::getSubQb()`](BuilderCriteria.md)) et doit retourner ce même objet après avoir chainé les critères désirés dessus.

```php
$repository->getQB()->selectFromSubQuery(
    Order::class,
    'o',
    fn ($orderRepo) => $orderRepo->countItem('id')->eq('book', 'book.id', customAlias: 'o'),
    subSelectAlias: 'ordersCount'
)->getArrayResults();
// SELECT book.*, (SELECT COUNT(o.id) FROM Order o WHERE o.book = book.id) as ordersCount FROM Book book
```

## Exemple d'usage complet

```php
class BookRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria, SelectCriteria, WhereCriteria;
    // ...
}

// Nombre de livres actifs :
$count = $bookRepository->getQB()->eq('enabled', true)->countItem('id')->getSingleScalarResult();

// Prix maximum :
$maxPrice = $bookRepository->getQB()->max('price')->getSingleScalarResult();
```

## Voir aussi

- [Expression/Expression](Expression/Expression.md), [Expression/Select](Expression/Select.md) — expressions utilisables avec `selectExpr()`.
- [BuilderCriteria](BuilderCriteria.md) — `getSingleScalarResult()`/`getArrayResults()` pour récupérer le résultat d'un `SELECT`.
