# Archivable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Archivable\Archivable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Archivable\ArchivableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Archivable/Archivable.php`, `ArchivableInterface.php`

## Rôle

Ajoute un flag booléen `archived` (par défaut `false`) à une entité, pour marquer un enregistrement comme archivé sans le supprimer.

## Propriété mappée

```php
#[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
private bool $archived = false;
```

Groupe de sérialisation : `archive`.

## API

### `isArchived(): bool`

Retourne `true` si l'entité est archivée.

### `setArchived(bool $archived): self`

Marque (ou démarque) l'entité comme archivée.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Archivable\Archivable;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    use Archivable;
}

$project = new Project();
$project->isArchived(); // false
$project->setArchived(true); // archive le projet
```

Pour exclure les projets archivés d'une liste : `WhereCriteria::eq('archived', false)` (voir [Domain/Repository/Criteria/WhereCriteria](../../Repository/Criteria/WhereCriteria.md)).
