# DoctrineOperator

- Enum : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\DoctrineOperator`
- Fichier source : `src/Domain/Repository/Criteria/DoctrineOperator.php`

## Rôle

Enum PHP (`string`-backed) énumérant les opérateurs de comparaison Doctrine supportés par [`ComplexBuilder::comparisonOperator()`](ComplexBuilder.md). Chaque cas correspond exactement au nom d'une méthode de l'objet `Doctrine\ORM\Query\Expr` (ex: `$operator->value` donne `'eq'`, `'lte'`, etc., appelé ensuite dynamiquement : `$expr->{$operator->value}(...)`).

Usage direct rare : cet enum est surtout un détail d'implémentation interne de `ComplexBuilder`. Vous ne l'utilisez presque jamais vous-même, sauf pour construire un opérateur dynamique/générique.

## Valeurs

| Cas | Valeur (`->value`) | Signification DQL |
|---|---|---|
| `DoctrineOperator::LTE` | `'lte'` | `<=` |
| `DoctrineOperator::LT` | `'lt'` | `<` |
| `DoctrineOperator::GTE` | `'gte'` | `>=` |
| `DoctrineOperator::GT` | `'gt'` | `>` |
| `DoctrineOperator::NOT_IN` | `'notIn'` | `NOT IN (...)` |
| `DoctrineOperator::IN` | `'in'` | `IN (...)` |
| `DoctrineOperator::EQ` | `'eq'` | `=` |
| `DoctrineOperator::NOT_EQ` | `'neq'` | `!=` |

## Exemple d'usage

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\DoctrineOperator;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\ComplexBuilder;

// Sélection dynamique d'un opérateur, par exemple depuis un filtre utilisateur :
$operator = match ($filterInput) {
    'gt' => DoctrineOperator::GT,
    'lt' => DoctrineOperator::LT,
    default => DoctrineOperator::EQ,
};

$condition = $complexBuilder->comparisonOperator($operator, 'price', 42);
```

## Voir aussi

- [ComplexBuilder](ComplexBuilder.md) — utilise cet enum en interne pour `eq`/`notEq`/`gt`/`gte`/`lt`/`lte`/`in`/`notIn`.
