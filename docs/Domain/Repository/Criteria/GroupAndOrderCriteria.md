# GroupAndOrderCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\GroupAndOrderCriteria`
- Fichier source : `src/Domain/Repository/Criteria/GroupAndOrderCriteria.php`

## Rôle

Trait apportant tri, regroupement et indexation des résultats, à combiner avec [`BuilderCriteria`](BuilderCriteria.md).

## API

### `random(): self`

Tri aléatoire (`ORDER BY rand()`). Attention, coûteux sur de grosses tables (à réserver aux petits volumes de données, ex: mise en avant aléatoire de quelques éléments).

```php
$repository->getQB()->random()->limit(5)->getResults();
```

### `order(string $field, string $direction = 'ASC', ?string $customAlias = null): self`

Ajoute un tri (`ORDER BY champ direction`). Chainable plusieurs fois pour un tri multi-critères (utilise `addOrderBy`, donc n'écrase pas les tris précédents).

```php
$repository->getQB()->order('category')->order('createdAt', 'DESC')->getResults();
```

### `groupBy(string $field, ?string $customAlias = null): self`

Ajoute un regroupement (`GROUP BY champ`). Chainable plusieurs fois (utilise `addGroupBy`).

```php
$repository->getQB()->select('category')->countItem('id', addSelect: true)->groupBy('category')->getArrayResults();
```

### `indexBy(string $field, ?string $customAlias = null): self`

Configure l'indexation Doctrine des résultats hydratés par la valeur d'un champ (`$qb->indexBy()`), directement au niveau de la requête (contrairement à [`BuilderCriteria::getResultsIndexedBy()`](BuilderCriteria.md), qui ré-indexe *après* hydratation, en PHP).

```php
$repository->getQB()->indexBy('slug')->getResults(); // tableau indexé par 'slug' dès l'hydratation
```

## Exemple d'usage complet

```php
$latestBooks = $bookRepository
    ->getQB()
    ->eq('enabled', true)
    ->order('publishedAt', 'DESC')
    ->limit(20)
    ->getResults();
```

## Voir aussi

- [BuilderCriteria](BuilderCriteria.md) — `getResultsIndexedBy()`/`getResultsIndexedById()`, alternative à `indexBy()` qui réindexe en PHP après hydratation.
