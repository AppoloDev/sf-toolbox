# ComplexBuilder

- Classe : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\ComplexBuilder`
- Fichier source : `src/Domain/Repository/Criteria/ComplexBuilder.php`

## Rôle

Constructeur d'expressions Doctrine bas niveau, injecté dans les callbacks de [`BuilderCriteria::complexQuery()`](BuilderCriteria.md). C'est le seul endroit où l'on peut combiner plusieurs conditions avec `andX`/`orX` dans une même clause `WHERE` — les méthodes équivalentes de [`WhereCriteria`](WhereCriteria.md) (plus simples d'usage) ne permettent pas cette composition.

Se construit avec une instance de `BuilderCriteriaInterface` (généralement le repository courant) :

```php
public function __construct(private readonly BuilderCriteriaInterface $builderCriteria)
```

Vous n'instanciez jamais `ComplexBuilder` vous-même — il est créé automatiquement par `complexQuery()` et passé à votre callback.

## API

### `andX(Composite|Comparison|string|null ...$conditions): ?Composite`

Combine plusieurs expressions avec un `AND` logique. Les conditions `null` sont automatiquement filtrées (pratique pour des filtres optionnels) ; si toutes les conditions sont `null` (ou aucune fournie), retourne `null` (donc rien n'est ajouté à la requête).

```php
$cb->andX(
    $cb->eq('enabled', true),
    $status !== null ? $cb->eq('status', $status) : null,
);
```

### `orX(Composite|Comparison|string|null ...$conditions): ?Composite`

Identique à `andX()` mais avec un `OR` logique.

```php
$cb->orX($cb->isNull('deletedAt'), $cb->gt('deletedAt', new \DateTime()));
```

### `searchIntoFields(?string $query, array $fields, ?string $customAlias = null): ?Composite`

Construit une recherche "plein texte" basique : découpe `$query` sur les espaces, et pour chaque terme, ajoute une clause `OR LIKE` (insensible à la casse) sur chacun des `$fields`. Cas particulier : si un champ s'appelle `id` et que le terme est un UUID valide, la comparaison se fait en exact-match binaire plutôt qu'en `LIKE`. Retourne `null` si `$query` est vide/`null` ou si `$fields` est vide.

```php
$cb->searchIntoFields('jean dupont', ['firstname', 'lastname', 'email']);
// (firstname LIKE '%jean%' OR lastname LIKE '%jean%' OR email LIKE '%jean%')
// OR (firstname LIKE '%dupont%' OR lastname LIKE '%dupont%' OR email LIKE '%dupont%')
```

### `eq(string $field, int|bool|string|\DateTimeInterface|null $value, ?string $customAlias = null): Comparison|Func`

Égalité stricte `champ = valeur`.

### `notEq(string $field, ..., ?string $customAlias = null): Comparison|Func`

Inégalité `champ != valeur`.

### `in(string $field, array|string $value, ?string $customAlias = null): Comparison|Func`

`champ IN (...)`.

### `notIn(string $field, string|array $value, ?string $customAlias = null): Comparison|Func`

`champ NOT IN (...)`.

### `gte(string $field, ..., ?string $customAlias = null): Comparison|Func`

`champ >= valeur`.

### `gt(string $field, ..., ?string $customAlias = null): Comparison|Func`

`champ > valeur`.

### `lte(string $field, ..., ?string $customAlias = null): Comparison|Func`

`champ <= valeur`.

### `lt(string $field, ..., ?string $customAlias = null): Comparison|Func`

`champ < valeur`.

### `isNull(string $field, ?string $customAlias = null): string`

`champ IS NULL`.

### `isNotNull(string $field, ?string $customAlias = null): string`

`champ IS NOT NULL`.

### `between(string $field, string|\DateTimeInterface|null $from, string|\DateTimeInterface|null $to, ?string $customAlias = null): string`

`champ BETWEEN :from AND :to` — pose lui-même les deux paramètres liés (noms générés dynamiquement via `uniqid()`, donc pas de risque de collision si utilisé plusieurs fois dans la même requête).

### `comparisonOperator(DoctrineOperator $operator, string $field, array|string|bool|int|\DateTimeInterface|null $value, ?string $customAlias = null): Comparison|Func`

Méthode générique sur laquelle `eq`/`notEq`/`in`/`notIn`/`gte`/`gt`/`lte`/`lt` sont toutes construites (voir [DoctrineOperator](DoctrineOperator.md) pour la liste des opérateurs). Pose automatiquement le paramètre lié, avec détection d'UUID (bind avec le type Doctrine `uuid` le cas échéant) et conversion de tableau (`array_map` sur chaque valeur via `getValue()`).

Vous n'avez normalement pas besoin d'appeler cette méthode directement — utilisez plutôt `eq`/`in`/etc.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\ComplexBuilder;

$books = $bookRepository->getQB()->complexQuery(fn (ComplexBuilder $cb) => $cb->andX(
    $cb->eq('enabled', true),
    $cb->orX(
        $cb->isNull('archivedAt'),
        $cb->gt('archivedAt', new \DateTimeImmutable('-1 month')),
    ),
    $authorId !== null ? $cb->eq('author', $authorId) : null,
))->getResults();
```

## Voir aussi

- [BuilderCriteria::complexQuery()](BuilderCriteria.md) — le point d'entrée qui fournit l'instance de `ComplexBuilder`.
- [WhereCriteria](WhereCriteria.md) — équivalents "simples" (une seule condition à la fois) construits au-dessus de `ComplexBuilder`.
- [DoctrineOperator](DoctrineOperator.md) — enum des opérateurs de comparaison disponibles.
