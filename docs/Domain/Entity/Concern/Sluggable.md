# Sluggable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Sluggable\Sluggable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Sluggable\SluggableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Sluggable/Sluggable.php`, `SluggableInterface.php`

## Rôle

Ajoute un champ `slug` généré automatiquement à partir d'un champ `title` de l'entité, via `Symfony\Component\String\Slugger\AsciiSlugger`, au moment du persist/update.

> **Prérequis** : l'entité doit porter `#[ORM\HasLifecycleCallbacks]`, sinon `updateSlug()` n'est jamais appelée.

> ⚠️ **Piège** : `updateSlug()` ne fait **rien** si l'entité ne définit pas une méthode `getTitle()` (vérifié via `method_exists($this, 'getTitle')`). Si votre entité n'a pas de `getTitle()`, le slug ne sera jamais généré automatiquement — pensez à appeler `setSlug()` vous-même dans ce cas.

## Propriété mappée

```php
#[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
private ?string $slug = null;
```

Groupe de sérialisation : `slug`.

## API

### `getSlug(): ?string`

Retourne le slug actuel de l'entité (`null` s'il n'a jamais été calculé/assigné).

### `setSlug(string $slug): self`

Force la valeur du slug. Utile quand l'entité n'a pas de `getTitle()`, ou pour imposer un slug personnalisé (ex: slug modifié manuellement par un utilisateur).

```php
$article->setSlug('mon-slug-personnalise');
```

### `updateSlug(): void`

Callback `#[ORM\PrePersist]`/`#[ORM\PreUpdate]` : si l'entité définit `getTitle(): string`, recalcule le slug à partir du titre courant (`AsciiSlugger`, mis en minuscules) et l'assigne via `setSlug()`. Ne fait rien sinon. Vous ne l'appelez normalement jamais vous-même.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Sluggable\Sluggable;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article
{
    use Sluggable;

    #[ORM\Column]
    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}

$article = new Article();
$article->setTitle('Mon Article Génial !');
$em->persist($article);
$em->flush();

$article->getSlug(); // "mon-article-genial"
```

Si `Article` n'avait pas de `getTitle()`, il faudrait faire :

```php
$article->setSlug('mon-article-genial'); // à gérer manuellement
```
