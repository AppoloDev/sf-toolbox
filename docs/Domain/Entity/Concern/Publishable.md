# Publishable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Publishable\Publishable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Publishable\PublishableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Publishable/Publishable.php`, `PublishableInterface.php`

## Rôle

Ajoute une fenêtre de publication (`publicationStartDate` → `publicationEndDate`) et un flag `enabled` à une entité. Se combine avec [Domain/Repository/Criteria/PublishableCriteria](../../Repository/Criteria/PublishableCriteria.md) côté repository pour filtrer les entités "publiées à la date courante".

> ⚠️ **Piège** : ce trait déclare une propriété privée `$enabled`, **exactement comme** [Activable](Activable.md). N'utilisez **jamais** `Publishable` et `Activable` ensemble sur la même entité (collision de nom de propriété — la compilation PHP échouera avec un conflit de trait).

## Propriétés mappées

```php
#[ORM\Column(type: Types::BOOLEAN)]
private bool $enabled = true;

#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
private ?\DateTimeInterface $publicationStartDate = null;

#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
private ?\DateTimeInterface $publicationEndDate = null;
```

Groupe de sérialisation : `publish`.

> Notez qu'il n'y a pas de `isEnabled()`/`setEnabled()` exposés publiquement par ce trait (contrairement à `Activable`) — seules les dates de publication ont des accesseurs.

## API

### `getPublicationStartDate(): ?\DateTimeInterface`

Retourne la date de début de publication (`null` = pas de borne basse, publié depuis toujours).

### `setPublicationStartDate(?\DateTimeInterface $publicationStartDate): self`

Définit la date à partir de laquelle l'entité devient visible.

### `getPublicationEndDate(): ?\DateTimeInterface`

Retourne la date de fin de publication (`null` = pas de borne haute, publié indéfiniment).

### `setPublicationEndDate(?\DateTimeInterface $publicationEndDate): self`

Définit la date à partir de laquelle l'entité cesse d'être visible.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Publishable\Publishable;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    use Publishable;
}

$article = new Article();
$article
    ->setPublicationStartDate(new \DateTimeImmutable('2026-01-01'))
    ->setPublicationEndDate(new \DateTimeImmutable('2026-12-31'));
```

Côté repository, pour ne récupérer que les articles publiés aujourd'hui, voir [PublishableCriteria::published()](../../Repository/Criteria/PublishableCriteria.md) :

```php
$articleRepository->getQB()->published()->getResults();
```
