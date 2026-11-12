# Activable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Activable\Activable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Activable\ActivatableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Activable/Activable.php`, `ActivatableInterface.php`

## Rôle

Ajoute un simple flag booléen `enabled` (activé/désactivé) à une entité, avec valeur par défaut `true`.

> ⚠️ **Piège** : ce trait déclare une propriété privée `$enabled`, **exactement comme** [Publishable](Publishable.md). N'utilisez **jamais** `Activable` et `Publishable` ensemble sur la même entité (collision de nom de propriété — la compilation PHP échouera).

## Propriété mappée

```php
#[ORM\Column(type: Types::BOOLEAN)]
private bool $enabled = true;
```

Groupe de sérialisation : `active`.

## API

### `isEnabled(): bool`

Retourne `true` si l'entité est active/activée.

### `setEnabled(bool $enabled): self`

Active (`true`) ou désactive (`false`) l'entité.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Activable\Activable;
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Activable\ActivatableInterface;

#[ORM\Entity(repositoryClass: UserAccountRepository::class)]
class UserAccount implements ActivatableInterface
{
    use Activable;
}

$account = new UserAccount();
$account->isEnabled(); // true (valeur par défaut)
$account->setEnabled(false); // désactive le compte
```

Pour filtrer côté repository : `WhereCriteria::eq('enabled', true)` (voir [Domain/Repository/Criteria/WhereCriteria](../../Repository/Criteria/WhereCriteria.md)).
