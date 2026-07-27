# Select (PartialExpr + PartialDtoExpr)

- Classes : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\Select\PartialExpr`, `PartialDtoExpr`
- Fichiers source : `src/Domain/Repository/Criteria/Expression/Select/PartialExpr.php`, `PartialDtoExpr.php`

## Rôle

Deux implémentations d'[`ExpressionInterface`](Expression.md) (voir ce fichier pour le contrat commun) dédiées à la **projection partielle** d'une entité dans un `SELECT` — c'est-à-dire ne charger que certains champs plutôt que l'entité complète, pour des raisons de performance.

- `PartialExpr` : projection "partielle" au sens Doctrine (syntaxe `partial alias.{champs}`), retourne toujours une instance de l'entité, mais avec seulement les champs demandés hydratés.
- `PartialDtoExpr` : projection vers un DTO arbitraire via la syntaxe `NEW` de DQL (hydratation d'un objet construit explicitement).

Les deux s'utilisent avec [`SelectCriteria::selectExpr()`](../SelectCriteria.md).

## API

### `PartialExpr::__construct(array $fields, ?string $customAlias = null)`

- `$fields` : liste des noms de champs à charger.
- `$customAlias` : alias DQL à utiliser (sinon, l'alias par défaut du repository).

### `PartialExpr::toString(BuilderCriteriaInterface $builderCriteria): string`

Génère `partial <alias>.{champ1, champ2, ...}`.

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\Select\PartialExpr;

$books = $repository->getQB()
    ->selectExpr(new PartialExpr(['id', 'title']))
    ->getResults();
// Retourne des instances de Book, mais seuls id/title sont hydratés
// (les autres champs lèveront une erreur si on tente d'y accéder sans les recharger)
```

### `PartialDtoExpr::__construct(array $fields, string $objectClass, ?string $customAlias = null)`

- `$fields` : liste des noms de champs, **dans l'ordre des arguments du constructeur** de `$objectClass`.
- `$objectClass` : FQCN de la classe DTO à instancier (doit avoir un constructeur acceptant ces champs dans cet ordre).
- `$customAlias` : alias DQL à utiliser (sinon, l'alias par défaut du repository).

### `PartialDtoExpr::toString(BuilderCriteriaInterface $builderCriteria): string`

Génère `NEW <FQCN>(<alias>.champ1, <alias>.champ2, ...)` — syntaxe DQL native permettant à Doctrine d'hydrater directement des objets non-entité.

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\Select\PartialDtoExpr;

final class BookSummaryDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
    ) {}
}

$summaries = $repository->getQB()
    ->selectExpr(new PartialDtoExpr(['id', 'title'], BookSummaryDto::class))
    ->getResults();
// $summaries est un tableau de BookSummaryDto, pas de Book
```

## Quand utiliser lequel ?

- `PartialExpr` : besoin de rester sur des instances de l'entité (ex: passer le résultat à du code qui attend `Book`), tout en limitant les colonnes chargées.
- `PartialDtoExpr` : besoin d'un objet de lecture dédié (ex: API, export), sans risque d'accéder par erreur à un champ non chargé.

## Voir aussi

- [Expression](Expression.md) — le contrat `ExpressionInterface` commun.
- [SelectCriteria::selectExpr()](../SelectCriteria.md) — point d'utilisation.
