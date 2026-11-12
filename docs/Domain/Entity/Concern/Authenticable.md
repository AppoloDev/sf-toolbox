# Authenticable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Authenticable\Authenticable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Authenticable\AuthenticableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Authenticable/Authenticable.php`, `AuthenticableInterface.php`

## Rôle

Transforme une entité en utilisateur authentifiable, en fournissant tous les champs et méthodes attendus par la sécurité Symfony (`UserInterface`/`PasswordAuthenticatedUserInterface`), plus un mécanisme de confirmation de compte par token.

> ⚠️ **Important** : le trait fournit les *méthodes* attendues par `UserInterface`/`PasswordAuthenticatedUserInterface` (mêmes noms, mêmes signatures), mais **ne déclare pas** `implements UserInterface` lui-même. C'est à l'entité qui utilise le trait de l'ajouter explicitement, sinon Symfony Security ne reconnaîtra pas la classe comme un utilisateur valide.

## Propriétés mappées

```php
#[ORM\Column(type: Types::STRING, length: 180, unique: true)]
private string $email;

#[ORM\Column(type: Types::JSON)]
private array $roles = [];

#[ORM\Column(type: Types::STRING)]
private string $password;

private ?string $plainPassword = ''; // non mappé en base

#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
private ?\DateTimeInterface $confirmationTokenExpiredAt;

#[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
private ?string $confirmationToken = null;
```

`email` et `roles` sont dans le groupe de sérialisation `authentication` ; `password`, `plainPassword`, `confirmationToken*` n'y sont volontairement pas (ne jamais exposer un mot de passe ou un token via l'API/sérialisation).

## API

### `getEmail(): string` / `setEmail(string $email): self`

Accesseurs de l'email, qui sert aussi d'identifiant unique de connexion.

### `getUserIdentifier(): string`

Requis par `UserInterface`. Retourne `getEmail()` — c'est l'email qui identifie l'utilisateur pour Symfony Security.

### `getUsername(): string`

Alias historique de `getUserIdentifier()`, retourne aussi l'email. Conservé pour compatibilité avec du code/des templates qui appellent encore `getUsername()`.

### `getRoles(): array`

Requis par `UserInterface`. Retourne le tableau `roles` **avec `ROLE_USER` systématiquement ajouté** (et dédoublonné via `array_unique`) — inutile d'ajouter `ROLE_USER` manuellement dans `setRoles()`.

```php
$user->setRoles(['ROLE_ADMIN']);
$user->getRoles(); // ['ROLE_ADMIN', 'ROLE_USER']
```

### `setRoles(array $roles): self`

Définit les rôles (hors `ROLE_USER`, ajouté automatiquement par `getRoles()`).

### `hasRole(string $role): bool`

Raccourci pour `in_array($role, $this->getRoles())`.

```php
if ($user->hasRole('ROLE_ADMIN')) { /* ... */ }
```

### `getPassword(): string` / `setPassword(string $password): self`

Requis par `PasswordAuthenticatedUserInterface`. Stocke le mot de passe **déjà hashé** (le hashing est fait en amont via le `PasswordHasherInterface` de Symfony, pas par ce trait).

### `getPlainPassword(): ?string` / `setPlainPassword(?string $plainPassword): self`

Stockage **temporaire, non persisté en base**, du mot de passe en clair saisi dans un formulaire (par exemple lors de l'inscription ou du changement de mot de passe), le temps de le hasher côté contrôleur/service avant d'appeler `setPassword()`.

### `getConfirmationToken(): ?string` / `setConfirmationToken(?string $confirmationToken): self`

Token utilisé pour un flux de confirmation de compte par email (lien cliquable envoyé par mail).

### `getConfirmationTokenExpiredAt(): ?\DateTimeInterface` / `setConfirmationTokenExpiredAt(?\DateTimeInterface $confirmationTokenExpiredAt): self`

Date d'expiration du token de confirmation — à vérifier vous-même côté contrôleur avant d'accepter une confirmation.

### `getSalt(): ?string`

Requis (historiquement) par les mécanismes d'authentification Symfony. Retourne toujours `null` — le "salt" est géré nativement par les algorithmes de hash modernes (bcrypt/argon2), donc inutile ici.

### `eraseCredentials(): void`

Requis par `UserInterface`, appelé par Symfony Security après authentification. **Stub vide par défaut** — si vous stockez des données sensibles temporaires sur l'entité (comme `plainPassword`), pensez à les effacer vous-même en surchargeant cette méthode dans votre entité :

```php
public function eraseCredentials(): void
{
    $this->setPlainPassword(null);
}
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Authenticable\Authenticable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Authenticable;
}

// Inscription :
$user = new User();
$user->setEmail('jean@example.com');
$user->setPlainPassword('motdepasseenclair');
$user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));
$user->setRoles(['ROLE_USER']);

// Sécurité Symfony utilisera automatiquement getUserIdentifier()/getRoles()/getPassword()
```
