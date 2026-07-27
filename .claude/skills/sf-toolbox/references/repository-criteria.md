# Repository Criteria

Namespace: `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\*`. A set of PHP traits meant to be composed into a Doctrine `ServiceEntityRepository` to get a fluent, chainable QueryBuilder API. There is no base repository class to extend — you build the repository yourself by `use`-ing the traits you need.

## Standard setup (what `make:domain:entity` generates)

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\GroupAndOrderCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\JoinCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\SelectCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\WhereCriteria;

class BookRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria;
    use GroupAndOrderCriteria;
    use WhereCriteria;
    use JoinCriteria;
    use SelectCriteria;
    // optionally also: DateCriteria, PublishableCriteria, GeolocalizableCriteria

    protected static string $alias = 'book'; // DQL alias used by every helper below

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
}
```

`protected static string $alias` is **required** — every trait method resolves the DQL alias via `getAlias()`/`getAliasField()`, defaulting to this static property unless a `$customAlias` argument is passed.

## Entry points — `BuilderCriteria` (the foundational trait)

- `getQB(?string $customAlias = null): self` — starts a new query (`createQueryBuilder($alias)`); call this first. Everything below is chained on the return value.
- `getSubQb(QueryBuilder $parentQb, string $alias): self` — starts a *sub*-query sharing parameters with `$parentQb` (used internally by `SelectCriteria::selectFromSubQuery`).
- `getBuilder()` / `getQueryBuilder()`: returns the underlying `Doctrine\ORM\QueryBuilder` (both do the same thing).
- Terminal methods: `getResults(): array`, `getSingleResult(): ?object`, `getArrayResults(): array`, `getSingleScalarResult(): ?int`, `getResultsIndexedBy(string $field): array`, `getResultsIndexedById(): array` (requires entity `implements IdentifiableInterface`).
- `limit(int $limit)`, `update()`, `delete()`, `set(string $field, $value, ?$customAlias)` — for UPDATE queries.
- `complexQuery(callable $callable): self` — the mechanism nearly every other trait method is built on. Receives a `ComplexBuilder` and expects it back an `andWhere`-able expression (or `null`, in which case nothing is added):
  ```php
  $repo->getQB()->complexQuery(fn (ComplexBuilder $cb) => $cb->andX(
      $cb->eq('status', 'active'),
      $cb->gte('createdAt', $someDate),
  ));
  ```
- `getValue(mixed $value): mixed` — auto-converts UUID strings to binary for Doctrine parameters; used internally, rarely called directly.
- `setParameter(string $name, $value, ?string $type = null): self` — sets on the *parent* QB when inside a sub-query, otherwise on the current one.

## `WhereCriteria` — simple filters (all delegate to `complexQuery`+`ComplexBuilder`)

`eq`, `notEq`, `in`, `notIn`, `isNull`, `isNotNull`, `gt`, `gte`, `lt`, `lte`, `between`, `searchIntoFields` — each takes `(string $field, ...$value, ?string $customAlias = null)` and returns `self`. `searchIntoFields(?string $query, array $fields, ?string $customAlias = null)` splits `$query` on spaces and OR-matches each term against each field (case-insensitive `LIKE`), with special-cased exact match when a field is `id` and the term is a valid UUID.

Also: `whereExpr`/`orWhereExpr`/`andWhereExpr(ExpressionInterface $expr)` for raw/composed DQL fragments — see "Expressions" below.

## `ComplexBuilder` — low-level expression builder

Passed into `complexQuery()` callbacks. Same comparison methods as `WhereCriteria` (`eq`, `notEq`, `in`, `notIn`, `gte`, `gt`, `lte`, `lt`, `isNull`, `isNotNull`, `between`) but each **returns a `Doctrine\ORM\Query\Expr\Comparison|Func` expression** instead of `self`, plus:
- `andX(...$conditions)`, `orX(...$conditions)` — composite expressions; `null` entries are filtered out, and an all-null/empty set returns `null` (so you can conditionally build a WHERE without `if` branches).
- `searchIntoFields(...)` — same as `WhereCriteria` but returns the raw `orX` expression.
- Automatically parameter-binds values it's given (unique generated names via `uniqid()`), and detects UUID strings to bind them with Doctrine type `uuid`.

Use `ComplexBuilder` directly (not `WhereCriteria`) when you need to combine several conditions with `andX`/`orX` inside one `complexQuery()` call, e.g. optional/conditional filters:
```php
$repo->getQB()->complexQuery(fn (ComplexBuilder $cb) => $cb->andX(
    $status !== null ? $cb->eq('status', $status) : null,
    $cb->orX($cb->isNull('deletedAt'), $cb->gt('deletedAt', new \DateTime())),
));
```

## `JoinCriteria`

- `with(string $field, string $joinAlias, ?string $customAlias = null, ?bool $addSelect = false): self` — `leftJoin` on an association field (e.g. `'book.author'`), optionally `addSelect`s the joined alias to avoid N+1.
- `join`/`leftJoin(string $className, string $joinAlias, string $conditions): self` — join an arbitrary class with an explicit `WITH` condition string.

## `SelectCriteria`

- `select`/`addSelect`/`selectDistinct(string $field, ?string $customAlias)`.
- `max`/`min`/`sum`/`countItem(string $field, ?string $customAlias, bool $addSelect = false)` — aggregate functions; `addSelect: true` adds to the existing SELECT instead of replacing it.
- `selectExpr(ExpressionInterface $expr)` — set the SELECT clause from an `ExpressionInterface` (see below), e.g. `PartialDtoExpr` to project into a DTO constructor.
- `selectFromSubQuery(string $entityClass, string $alias, callable $cb, ?string $subSelectAlias = null): self` — embeds a correlated subquery built via `getSubQb()`; `$cb` receives the sub-repository instance already scoped with `getSubQb($this->qb, $alias)` and must return it after chaining criteria on it.

## `GroupAndOrderCriteria`

- `order(string $field, string $direction = 'ASC', ?string $customAlias = null)`, `groupBy(string $field, ?string $customAlias = null)`, `random()` (`ORDER BY rand()`), `indexBy(string $field, ?string $customAlias = null)`.

## `DateCriteria` (opt-in trait)

- `date(string $field, \DateTimeInterface $date, ?string $customAlias = null)` — matches the whole calendar day (00:00:00–23:59:59).
- `dateBetween(string $field, \DateTimeInterface $from, \DateTimeInterface $to, ?string $customAlias = null)`.
- `dateNotExpired`/`dateExpired(string $field, ?\DateTimeInterface $customDate = null, ?string $customAlias = null)` — compares `$field` against now (or `$customDate`).

## `PublishableCriteria` (opt-in trait, pairs with the `Publishable` entity concern)

- `published(string $fieldFrom = 'publicationStartDate', string $fieldTo = 'publicationEndDate', ?string $customAlias = null, ?\DateTime $currentDate = null): self` — filters rows where `$currentDate` (default: today, full-day range) falls within `[$fieldFrom, $fieldTo]`, treating `null` bounds as "no limit" on that side.

## `GeolocalizableCriteria` (opt-in trait, pairs with the `Geolocalizable` entity concern)

- `around(float $lat, float $lng, int $radius = 10, ?string $customAlias, ?string $latField = 'lat', ?string $lngField = 'lng'): self` — Haversine-formula distance filter (km), binds `:radius`.
- `bounds(array $bounds, ...): self` — expects keys `slat`/`nlat`/`slng`/`nlng` (south/north lat, south/north lng), filters both ranges with `between()` when the corresponding keys are present.

## Expressions (`Domain\Repository\Criteria\Expression\*`)

Implement `ExpressionInterface { toString(BuilderCriteriaInterface $builderCriteria): string }` to inject raw or computed DQL fragments into `select`/`where`:
- `StringExpr(string $str)` — literal passthrough.
- `Select\PartialExpr(array $fields, ?string $customAlias = null)` — renders `partial alias.{field1, field2}`.
- `Select\PartialDtoExpr(array $fields, string $objectClass, ?string $customAlias = null)` — renders `NEW Some\Dto(alias.field1, alias.field2)` (DQL `NEW` object-hydration expression).

Use with `selectExpr()`/`whereExpr()`/`orWhereExpr()`/`andWhereExpr()`.
