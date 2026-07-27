# BuilderCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteria`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface`
- Fichiers source : `src/Domain/Repository/Criteria/BuilderCriteria.php`, `BuilderCriteriaInterface.php`

## Rôle

Trait **fondamental** de toute la couche Criteria : c'est lui qui initialise le `QueryBuilder` Doctrine et fournit les méthodes terminales (exécution de la requête). Tous les autres traits de `Domain\Repository\Criteria\*` ([WhereCriteria](WhereCriteria.md), [JoinCriteria](JoinCriteria.md), [SelectCriteria](SelectCriteria.md), [GroupAndOrderCriteria](GroupAndOrderCriteria.md), [DateCriteria](DateCriteria.md), [PublishableCriteria](PublishableCriteria.md), [GeolocalizableCriteria](GeolocalizableCriteria.md)) s'appuient dessus et **doivent toujours être combinés avec lui**.

À utiliser dans une classe `extends ServiceEntityRepository implements BuilderCriteriaInterface`, avec une propriété statique `protected static string $alias` (l'alias DQL utilisé par toutes les méthodes, sauf si un `$customAlias` est explicitement fourni).

## API

### `getQB(?string $customAlias = null): self`

Point d'entrée : initialise un nouveau `QueryBuilder` (`createQueryBuilder($alias)`) et le stocke en interne. **Toujours le premier appel de la chaîne.**

- `$customAlias` : si fourni, utilisé comme alias DQL à la place de `static::$alias`.

```php
$repository->getQB(); // équivalent à createQueryBuilder('book') si $alias = 'book'
$repository->getQB('b'); // alias personnalisé "b"
```

### `getSubQb(QueryBuilder $parentQb, string $alias): self`

Initialise une **sous-requête**, dont les paramètres seront posés sur `$parentQb` (la requête englobante) plutôt que sur la sous-requête elle-même — nécessaire car Doctrine ne permet pas de binder un paramètre directement sur une sous-requête. Utilisé en interne par [SelectCriteria::selectFromSubQuery()](SelectCriteria.md).

### `getBuilder(): QueryBuilder` / `getQueryBuilder(): QueryBuilder`

Retourne le `QueryBuilder` Doctrine sous-jacent (les deux méthodes sont strictement équivalentes). Utile pour passer la requête à un service tiers (ex: `KnpPaginatorBundle::paginate()`) ou pour terminer la construction avec des méthodes natives de Doctrine non couvertes par la couche Criteria.

```php
$qb = $repository->getQB()->eq('enabled', true)->getBuilder();
$pagination = $paginator->paginate($qb, $page, 12);
```

### `useQBMethod(string $methodName, array $params = []): self`

> **Dépréciée** — appelle dynamiquement une méthode du `QueryBuilder` natif (`$this->qb->$methodName(...$params)`). Préférez toujours une méthode dédiée de la couche Criteria, ou `getBuilder()` pour manipuler le QueryBuilder directement de façon typée.

### `set(string $field, int|float|bool|string|null $value, ?string $customAlias = null): self`

Ajoute une clause `SET` (pour une requête `UPDATE`).

```php
$repository->getQB()->update()->set('enabled', false)->eq('id', $id)->getBuilder()->getQuery()->execute();
```

### `update(): self`

Bascule la requête en mode `UPDATE` (`$this->qb->update()`).

### `delete(): self`

Bascule la requête en mode `DELETE` (`$this->qb->delete()`).

### `limit(int $limit): self`

Limite le nombre de résultats (`setMaxResults`).

### `getSingleResult(): ?object`

Force `limit(1)` puis exécute la requête, retourne le premier résultat ou `null` s'il n'y en a pas (contrairement à `Query::getSingleResult()` natif de Doctrine, ne lève **jamais** d'exception s'il n'y a pas de résultat).

```php
$book = $repository->getQB()->eq('slug', $slug)->getSingleResult();
```

### `getSingleScalarResult(): ?int`

Exécute la requête et retourne un unique résultat scalaire castée en `int` (typiquement pour un `COUNT`/`SUM`/etc. — voir [SelectCriteria](SelectCriteria.md)).

```php
$total = $repository->getQB()->countItem('id')->getSingleScalarResult();
```

### `getResults(): array`

Exécute la requête et retourne le tableau des résultats hydratés (objets entité par défaut). C'est la méthode terminale la plus utilisée.

### `getArrayResults(): array`

Exécute la requête et retourne le résultat sous forme de tableaux associatifs (hydratation `ARRAY` de Doctrine) plutôt que d'objets.

### `getResultsIndexedBy(string $field): array`

Exécute la requête et ré-indexe le tableau de résultats par la valeur d'un champ donné (obtenu via `get<Field>()` sur chaque résultat). Convertit automatiquement les clés `Uuid` en chaîne.

```php
$booksBySlug = $repository->getQB()->getResultsIndexedBy('slug');
// ['mon-livre' => Book {...}, 'autre-livre' => Book {...}]
```

### `getResultsIndexedById(): array`

Raccourci de `getResultsIndexedBy()` spécifique à l'identifiant : exécute la requête et indexe les résultats par `(string) $item->getId()`. **Nécessite que l'entité implémente [`IdentifiableInterface`](../../Entity/Concern/Identifiable.md).**

```php
$booksById = $repository->getQB()->getResultsIndexedById();
// ['0195...uuid...' => Book {...}, ...]
```

### `complexQuery(callable $callable): self`

Mécanisme central sur lequel reposent la plupart des autres traits (`WhereCriteria`, `PublishableCriteria`, etc.). `$callable` reçoit une instance de [`ComplexBuilder`](ComplexBuilder.md) et doit retourner une expression Doctrine (`Composite|Comparison|Func|string`) ou `null`. Si une expression est retournée, elle est ajoutée en `andWhere` ; si `null`, rien n'est ajouté (utile pour des filtres conditionnels).

```php
$repository->getQB()->complexQuery(
    fn (ComplexBuilder $cb) => $cb->andX(
        $cb->eq('enabled', true),
        $status !== null ? $cb->eq('status', $status) : null, // filtre optionnel
    )
);
```

### `getAlias(?string $customAlias): string`

Retourne `$customAlias` s'il est fourni, sinon `static::$alias`. Utilisé en interne par (quasiment) toutes les autres méthodes de la couche Criteria.

### `getAliasField(?string $customAlias, string $field): string`

Retourne `"<alias>.<field>"`, prêt à être utilisé dans une expression DQL. Utilisé en interne partout où un nom de champ est manipulé.

```php
$repository->getAliasField(null, 'title'); // "book.title" (si $alias = 'book')
$repository->getAliasField('b', 'title');  // "b.title"
```

### `setParameter(string $paramName, mixed $value, ?string $type = null): self`

Pose un paramètre lié sur le `QueryBuilder` — sur le `QueryBuilder` **parent** si on est dans une sous-requête (voir `getSubQb()`), sinon sur le `QueryBuilder` courant.

### `getValue(mixed $value): mixed`

Convertit automatiquement une chaîne UUID valide en sa forme binaire (`Uuid::fromString($value)->toBinary()`), sinon retourne la valeur telle quelle. Utilisé en interne par [`ComplexBuilder`](ComplexBuilder.md) pour que vous puissiez passer une chaîne UUID brute à `eq()`/`in()`/etc. sans conversion manuelle.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\WhereCriteria;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\GroupAndOrderCriteria;

class BookRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria;
    use WhereCriteria;
    use GroupAndOrderCriteria;

    protected static string $alias = 'book';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
}

$books = $bookRepository
    ->getQB()
    ->eq('enabled', true)
    ->order('createdAt', 'DESC')
    ->limit(10)
    ->getResults();
```

## Voir aussi

- [ComplexBuilder](ComplexBuilder.md) — le constructeur d'expressions utilisé par `complexQuery()`.
- [WhereCriteria](WhereCriteria.md), [JoinCriteria](JoinCriteria.md), [SelectCriteria](SelectCriteria.md), [GroupAndOrderCriteria](GroupAndOrderCriteria.md) — traits complémentaires courants.
