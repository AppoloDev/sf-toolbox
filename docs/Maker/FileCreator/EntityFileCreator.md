# EntityFileCreator

- Classe : `AppoloDev\SFToolboxBundle\Maker\FileCreator\EntityFileCreator`
- Fichier source : `src/Maker/FileCreator/EntityFileCreator.php`
- Étend : [`AbstractFileCreator`](AbstractFileCreator.md)

## Rôle

Générateur concret utilisé par [`MakeDomainEntityCommand`](../Command/MakeDomainEntityCommand.md) pour créer une entité + son repository.

## Mapping des templates

```php
protected array $mapping = [
    'doctrine/Entity.tpl'     => '/src/Domain/##DOMAIN##/Entity/##ENTITY##.php',
    'doctrine/Repository.tpl' => '/src/Domain/##DOMAIN##/Repository/##ENTITY##Repository.php',
];
```

## API

### `replaceVars(string $value): string` *(protected)*

Remplace, dans le contenu du template ou dans le chemin cible :

| Placeholder | Remplacé par |
|---|---|
| `##DOMAIN##` | `$this->domain` (tel quel) |
| `##ENTITY##` | `$this->entity` (tel quel) |
| `##ENTITYLOWER##` | `strtolower($this->entity)` |

Hérite de `init()`, `filesExist()`, `create()` de [`AbstractFileCreator`](AbstractFileCreator.md) sans les surdéfinir.

## Exemple d'usage

Ce service est injecté automatiquement (autowiring) dans [`MakeDomainEntityCommand`](../Command/MakeDomainEntityCommand.md) — vous ne l'instanciez jamais directement, mais voici ce qui se passe en interne :

```php
$fileCreator->init(['domain' => 'Catalog', 'entity' => 'Book']);

if (!$fileCreator->filesExist()) {
    $fileCreator->create();
    // crée :
    //   src/Domain/Catalog/Entity/Book.php
    //   src/Domain/Catalog/Repository/BookRepository.php
}
```

## Voir aussi

- [Command/MakeDomainEntityCommand](../Command/MakeDomainEntityCommand.md) — utilisateur de ce service.
- [AbstractFileCreator](AbstractFileCreator.md) — logique commune héritée.
