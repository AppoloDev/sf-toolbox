# Domain Entity Concerns

Namespace: `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\<Name>\<Name>` (trait) and `<Name>Interface` (interface), one sub-namespace per concern. `use` the trait in an entity; optionally `implements` the interface if other code needs to type-hint the capability instead of the concrete entity.

All traits map Doctrine columns via PHP attributes (`#[ORM\Column]`) and Symfony Serializer `#[Groups]` (group name in parentheses below). None declare an `#[ORM\Entity]` — they're meant to be composed into a real entity class.

## Identifiable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable\Identifiable;
```
- `?Uuid $id` — primary key, `ORM\Id`, `CUSTOM` strategy via `UuidGenerator`, column type `uuid`.
- `getId(): ?Uuid` only (no setter — id is generated).
- Used by nearly every entity generated via `make:domain:entity` (see [maker-commands.md](maker-commands.md)).

## Timestampable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Timestampable\Timestampable;
```
- `?\DateTimeInterface $createdAt`, `$updatedAt` (group `timestamp`), DB default `CurrentTimestamp()`.
- `#[ORM\PrePersist, ORM\PreUpdate] updatedTimestamps()`: sets `updatedAt` to now on every persist/update, and sets `createdAt` to now the first time (if still null). Requires the entity class to have `#[ORM\HasLifecycleCallbacks]` (the `make:domain:entity` template adds this).
- `getCreatedAt/setCreatedAt/getUpdatedAt/setUpdatedAt`.

## Sluggable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Sluggable\Sluggable;
```
- `?string $slug` (group `slug`).
- `#[ORM\PrePersist, ORM\PreUpdate] updateSlug()`: **only acts if the entity also defines `getTitle()`** (checked with `method_exists`); slugifies `getTitle()` via `AsciiSlugger` and lowercases it. If there's no `getTitle()`, this method is a silent no-op — set the slug manually in that case.
- Requires `#[ORM\HasLifecycleCallbacks]` on the entity.

## Publishable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Publishable\Publishable;
```
- `bool $enabled = true`, `?\DateTimeInterface $publicationStartDate`, `$publicationEndDate` (group `publish`).
- Pair with `PublishableCriteria` on the repository to filter to "currently published" rows (see [repository-criteria.md](repository-criteria.md)).
- Note: reuses the field name `$enabled` — do not combine `Publishable` and `Activable` in the same entity (property collision).

## Activable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Activable\Activable;
```
- `bool $enabled = true` (group `active`). `isEnabled()/setEnabled()`.
- Same `$enabled` property name as `Publishable` — mutually exclusive with it.

## Archivable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Archivable\Archivable;
```
- `bool $archived = false` (group `archive`), DB default `false`. `isArchived()/setArchived()`.

## Blockable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Blockable\Blockable;
```
- `bool $blocked = false` (group `block`). `isBlocked()/setBlocked()`.

## Deletable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Deletable\Deletable;
```
- `bool $deleted = false` (group `delete`), DB default `false`. `isDeleted()/setDeleted()`.
- This is a soft-delete flag only — nothing in the bundle filters it out automatically; add a `WhereCriteria::eq('deleted', false)` call (or similar) in repository queries yourself.

## Authenticable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Authenticable\Authenticable;
```
- Fields (group `authentication` on email/roles only): `string $email` (unique), `array $roles = []`, `string $password`, `?string $plainPassword`, `?\DateTimeInterface $confirmationTokenExpiredAt`, `?string $confirmationToken`.
- Implements the shape of Symfony's `UserInterface`/`PasswordAuthenticatedUserInterface` (`getUserIdentifier`, `getUsername`, `getRoles` — always appends `ROLE_USER`, `hasRole`, `getPassword`, `getSalt` returns `null`, `eraseCredentials` — no-op stub, clear `plainPassword` yourself if needed) **without declaring `implements UserInterface`**. Add `implements UserInterface, PasswordAuthenticatedUserInterface` on the entity itself.

## Geolocalizable
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Geolocalizable\Geolocalizable;
```
- Fields (group `localisation`): `?string $address`, `?string $zipCode` (length 5), `?string $city`, `?float $lat`, `?float $lng`, `?string $formattedAddress`.
- Pair with `GeolocalizableType` (form, see [form-types.md](form-types.md)) and `GeolocalizableCriteria` (repository, see [repository-criteria.md](repository-criteria.md)).

## FileUpload
```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\FileUpload\FileUploadInterface;
```
- Interface only (`getFilename(): ?string`, `getFile(): ?File`, `getFilePath(): ?string`) — no trait implementation is provided; the consuming project implements these methods itself (e.g. backed by VichUploaderBundle or a custom uploader).
