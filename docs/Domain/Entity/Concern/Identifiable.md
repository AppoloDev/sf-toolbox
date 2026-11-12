# Identifiable

- Trait : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable\Identifiable`
- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable\IdentifiableInterface`
- Fichiers source : `src/Domain/Entity/Concern/Identifiable/Identifiable.php`, `IdentifiableInterface.php`

## Rôle

Ajoute une clé primaire de type `Uuid` (Symfony `symfony/uid`) à une entité Doctrine, générée automatiquement par la base via un générateur personnalisé (`UuidGenerator`). C'est le concern de base utilisé par (quasiment) toutes les entités du projet, y compris celles générées par `make:domain:entity` (voir [Maker/Command/MakeDomainEntityCommand](../../../Maker/Command/MakeDomainEntityCommand.md)).

## Propriété mappée

```php
#[ORM\Column(type: 'uuid', unique: true)]
#[ORM\GeneratedValue(strategy: 'CUSTOM')]
#[ORM\CustomIdGenerator(class: UuidGenerator::class)]
#[ORM\Id]
private ?Uuid $id = null;
```

L'identifiant est `null` tant que l'entité n'a pas été persistée (Doctrine génère l'UUID au moment du `flush`, via le `UuidGenerator`).

## API

### `getId(): ?Uuid`

Retourne l'identifiant Uuid de l'entité, ou `null` si l'entité n'a pas encore été persistée.

Il n'y a **volontairement pas de `setId()`** : l'identifiant est entièrement géré par Doctrine, on ne doit jamais l'assigner manuellement.

```php
$book = new Book();
$book->getId(); // null, tant que l'entité n'est pas flush()
$em->persist($book);
$em->flush();
$book->getId(); // instance de Symfony\Component\Uid\Uuid
```

## `IdentifiableInterface`

Interface à utiliser pour typer du code générique qui a besoin de manipuler "n'importe quelle entité identifiable" sans connaître la classe concrète — par exemple `BuilderCriteria::getResultsIndexedById()` (voir [Domain/Repository/Criteria/BuilderCriteria](../../Repository/Criteria/BuilderCriteria.md)) exige que l'entité implémente cette interface :

```php
class Book implements IdentifiableInterface
{
    use Identifiable;
}
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable\Identifiable;
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable\IdentifiableInterface;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book implements IdentifiableInterface
{
    use Identifiable;
}

// Comparer/afficher l'UUID sous forme de chaîne :
$idAsString = (string) $book->getId();
```

## Voir aussi

- [Domain/Repository/Criteria/BuilderCriteria](../../Repository/Criteria/BuilderCriteria.md) — `getResultsIndexedById()` s'appuie sur cette interface.
- [Utils/UuidUtils](../../../Utils/UuidUtils.md) — détection de chaînes UUID, utilisée en interne par la couche Criteria.
