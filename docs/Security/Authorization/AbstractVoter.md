# AbstractVoter

- Classe : `AppoloDev\SFToolboxBundle\Security\Authorization\AbstractVoter`
- Fichier source : `src/Security/Authorization/AbstractVoter.php`
- Étend : `Symfony\Component\Security\Core\Authorization\Voter\Voter`

## Rôle

Classe de base pour écrire des Voters Symfony, apportant deux raccourcis de vérification de rôle au-dessus du `Voter` natif. Ne dispense pas d'implémenter `supports()` et `voteOnAttribute()` vous-même — seule la logique de vérification de rôle est mutualisée.

C'est la classe parente des Voters générés par [`make:scaffold`](../../Maker/Command/MakeScaffoldCommand.md).

## Constructeur

### `__construct(Security $security)`

`$security` (`Symfony\Bundle\SecurityBundle\Security`) est injecté automatiquement par autowiring.

## API

### `canAllow(array|string $roles): bool` *(protected)*

Retourne `true` si l'utilisateur courant possède **au moins un** des rôles donnés (sémantique `OR`). Accepte un rôle unique (`string`) ou une liste (`array`).

```php
protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
{
    return $this->canAllow(['ROLE_EDITOR', 'ROLE_ADMIN']); // éditeur OU admin
}
```

### `canAllowAdmin(): bool` *(protected)*

Raccourci équivalent à `$this->canAllow('ROLE_ADMIN')`.

```php
protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
{
    return $this->canAllowAdmin();
}
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Security\Authorization\AbstractVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class BookVoter extends AbstractVoter
{
    public const string EDIT = 'book_edit';
    public const string DELETE = 'book_delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true) && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            self::EDIT => $this->canAllow(['ROLE_EDITOR', 'ROLE_ADMIN']),
            self::DELETE => $this->canAllowAdmin(),
            default => false,
        };
    }
}
```

## Voir aussi

- [Maker/Command/MakeScaffoldCommand](../../Maker/Command/MakeScaffoldCommand.md) — génère un Voter étendant cette classe.
