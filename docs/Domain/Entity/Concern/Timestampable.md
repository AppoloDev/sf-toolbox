# Timestampable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Timestampable\Timestampable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Timestampable\TimestampableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Timestampable/Timestampable.php`, `TimestampableInterface.php`

## Rôle

Ajoute deux dates `createdAt`/`updatedAt` à une entité, automatiquement renseignées par des callbacks de cycle de vie Doctrine (`#[ORM\PrePersist]`/`#[ORM\PreUpdate]`). Aucune intervention manuelle n'est nécessaire une fois le trait utilisé : les dates se mettent à jour toutes seules à chaque persist/update.

> **Prérequis** : l'entité doit porter l'attribut `#[ORM\HasLifecycleCallbacks]`, sinon les callbacks `#[ORM\PrePersist]`/`#[ORM\PreUpdate]` ne sont jamais appelés par Doctrine.

## Propriétés mappées

```php
#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => new CurrentTimestamp()])]
private ?\DateTimeInterface $createdAt = null;

#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => new CurrentTimestamp()])]
private ?\DateTimeInterface $updatedAt = null;
```

Groupe de sérialisation : `timestamp`.

## API

### `updatedTimestamps(): void`

Callback appelé automatiquement par Doctrine avant chaque `INSERT` et `UPDATE` (`#[ORM\PrePersist]`, `#[ORM\PreUpdate]`). Met toujours `updatedAt` à la date/heure courante, et initialise `createdAt` à la date/heure courante **uniquement s'il est encore `null`** (donc uniquement au tout premier persist).

Vous n'appelez normalement jamais cette méthode vous-même — Doctrine s'en charge.

### `getCreatedAt(): ?\DateTimeInterface`

Retourne la date de création de l'entité (`null` avant le premier `flush()`).

### `setCreatedAt(?\DateTimeInterface $createdAt): self`

Force la date de création. Utile en import/migration de données, ou en test, quand on veut simuler une entité créée dans le passé.

```php
$book->setCreatedAt(new \DateTimeImmutable('2020-01-01'));
```

### `getUpdatedAt(): ?\DateTimeInterface`

Retourne la date de dernière mise à jour.

### `setUpdatedAt(?\DateTimeInterface $updatedAt): self`

Force la date de mise à jour manuellement (rarement nécessaire, puisque `updatedTimestamps()` la recalcule automatiquement à chaque flush).

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Timestampable\Timestampable;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\HasLifecycleCallbacks] // requis pour que les callbacks se déclenchent
class Book
{
    use Timestampable;
}

$book = new Book();
$em->persist($book);
$em->flush();

$book->getCreatedAt(); // date du jour
$book->getUpdatedAt(); // même date

// ... plus tard, après modification et flush() :
$book->getUpdatedAt(); // mise à jour automatique
$book->getCreatedAt(); // inchangée
```

## Voir aussi

- [Domain/Repository/Criteria/DateCriteria](../../Repository/Criteria/DateCriteria.md) — filtres de requête par date, souvent utilisés sur `createdAt`/`updatedAt`.
- [Domain/Repository/Criteria/GroupAndOrderCriteria](../../Repository/Criteria/GroupAndOrderCriteria.md) — pour trier par `updatedAt` (`->order('updatedAt', 'DESC')`).
