# Expression (ExpressionInterface + StringExpr)

- Interface : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\ExpressionInterface`
- Classe : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\StringExpr`
- Fichiers source : `src/Domain/Repository/Criteria/Expression/ExpressionInterface.php`, `StringExpr.php`

## Rôle

`ExpressionInterface` est le contrat commun à toutes les "expressions" injectables dans [`SelectCriteria::selectExpr()`](../SelectCriteria.md) et [`WhereCriteria::whereExpr()`/`orWhereExpr()`/`andWhereExpr()`](../WhereCriteria.md) : un objet capable de se transformer en fragment de chaîne DQL, avec accès au `BuilderCriteriaInterface` courant (pour résoudre l'alias DQL via `getAlias()`/`getAliasField()`).

`StringExpr` est l'implémentation la plus simple : un passthrough littéral, sans aucune logique de résolution d'alias.

Voir aussi [Select](Select.md) pour les implémentations `PartialExpr`/`PartialDtoExpr`, plus élaborées.

## API

### `ExpressionInterface::toString(BuilderCriteriaInterface $builderCriteria): string`

Doit retourner le fragment DQL correspondant à l'expression. Reçoit le `BuilderCriteriaInterface` (le repository courant), ce qui permet à une implémentation de résoudre dynamiquement l'alias DQL (`$builderCriteria->getAlias(...)`) plutôt que de le coder en dur.

### `StringExpr::__construct(string $str)`

Construit l'expression à partir d'une chaîne DQL déjà prête.

### `StringExpr::toString(BuilderCriteriaInterface $builderCriteria): string`

Retourne simplement la chaîne passée au constructeur, telle quelle (ignore le `$builderCriteria` reçu).

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\StringExpr;

$books = $bookRepository
    ->getQB()
    ->andWhereExpr(new StringExpr('book.publishedYear >= 2020'))
    ->getResults();
```

Pour écrire votre propre expression réutilisable et consciente de l'alias :

```php
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\BuilderCriteriaInterface;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\Expression\ExpressionInterface;

final class LowerFieldExpr implements ExpressionInterface
{
    public function __construct(private string $field) {}

    public function toString(BuilderCriteriaInterface $builderCriteria): string
    {
        return 'LOWER('.$builderCriteria->getAliasField(null, $this->field).')';
    }
}

$repository->getQB()->selectExpr(new LowerFieldExpr('title'))->getArrayResults();
```

## Voir aussi

- [Select](Select.md) — expressions de projection (`PartialExpr`, `PartialDtoExpr`).
- [SelectCriteria::selectExpr()](../SelectCriteria.md), [WhereCriteria::whereExpr()/andWhereExpr()/orWhereExpr()](../WhereCriteria.md) — points d'utilisation des expressions.
