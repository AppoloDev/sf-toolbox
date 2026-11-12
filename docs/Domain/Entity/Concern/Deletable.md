# Deletable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Deletable\Deletable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Deletable\DeletableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Deletable/Deletable.php`, `DeletableInterface.php`

## Rôle

Ajoute un flag booléen `deleted` (par défaut `false`) pour implémenter une suppression logique ("soft delete").

> ⚠️ **Ce trait ne fait que stocker le flag.** Rien dans le bundle ne filtre automatiquement les entités `deleted = true` des requêtes (pas de filtre Doctrine global, pas de `WhereCriteria` implicite) — c'est à vous d'ajouter le filtre dans vos repositories, par exemple avec `WhereCriteria::eq('deleted', false)` (voir [Domain/Repository/Criteria/WhereCriteria](../../Repository/Criteria/WhereCriteria.md)).

## Propriété mappée

```php
#[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
private bool $deleted = false;
```

Groupe de sérialisation : `delete`.

## API

### `isDeleted(): bool`

Retourne `true` si l'entité est marquée comme supprimée.

### `setDeleted(bool $deleted): self`

Marque (ou restaure) l'entité comme supprimée.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Deletable\Deletable;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    use Deletable;
}

// "Suppression" logique, sans toucher à la base :
$comment->setDeleted(true);
$em->flush();
```

Dans le repository, pensez à exclure les éléments supprimés vous-même :

```php
$commentRepository->getQB()->eq('deleted', false)->getResults();
```
