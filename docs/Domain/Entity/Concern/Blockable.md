# Blockable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Blockable\Blockable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Blockable\BlockableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Blockable/Blockable.php`, `BlockableInterface.php`

## Rôle

Ajoute un flag booléen `blocked` (par défaut `false`) à une entité — typiquement pour bloquer un compte utilisateur ou une ressource sans la supprimer ni simplement la désactiver (sémantiquement distinct de `Activable`/`enabled` : "bloqué" implique souvent une action de modération, alors que "désactivé" peut être un choix de l'utilisateur lui-même).

## Propriété mappée

```php
#[ORM\Column(type: Types::BOOLEAN)]
private bool $blocked = false;
```

Groupe de sérialisation : `block`.

## API

### `isBlocked(): bool`

Retourne `true` si l'entité est bloquée.

### `setBlocked(bool $blocked): self`

Bloque (`true`) ou débloque (`false`) l'entité.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Blockable\Blockable;

#[ORM\Entity(repositoryClass: UserAccountRepository::class)]
class UserAccount
{
    use Blockable;
}

$account = new UserAccount();
$account->setBlocked(true); // suite à un signalement, par exemple

if ($account->isBlocked()) {
    throw new AccessDeniedException('Ce compte est bloqué.');
}
```
