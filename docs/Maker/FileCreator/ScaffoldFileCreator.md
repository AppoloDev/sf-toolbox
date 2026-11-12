# ScaffoldFileCreator

- Classe : `AppoloDev\SFToolboxBundle\Maker\FileCreator\ScaffoldFileCreator`
- Fichier source : `src/Maker/FileCreator/ScaffoldFileCreator.php`
- Étend : [`AbstractFileCreator`](AbstractFileCreator.md)

## Rôle

Générateur concret utilisé par [`MakeScaffoldCommand`](../Command/MakeScaffoldCommand.md) pour créer un CRUD complet (contrôleurs, formulaire, voter, templates) à partir des templates sous `maker/scaffold/`.

## Mapping des templates

```php
protected array $mapping = [
    'scaffold/controller/AddController.tpl'    => '/src/Http/##AREA##/Controller/##ENTITY##/Add##ENTITY##Controller.php',
    'scaffold/controller/EditController.tpl'   => '/src/Http/##AREA##/Controller/##ENTITY##/Edit##ENTITY##Controller.php',
    'scaffold/controller/DeleteController.tpl' => '/src/Http/##AREA##/Controller/##ENTITY##/Delete##ENTITY##Controller.php',
    'scaffold/controller/ListController.tpl'   => '/src/Http/##AREA##/Controller/##ENTITY##/List##ENTITY##Controller.php',
    'scaffold/controller/ExportController.tpl' => '/src/Http/##AREA##/Controller/##ENTITY##/Export##ENTITY##Controller.php',
    'scaffold/form/FormType.tpl'                => '/src/Http/##AREA##/Form/##ENTITY##/##ENTITY##FormType.php',
    'scaffold/voter/Voter.tpl'                  => '/src/Http/##AREA##/Voter/##ENTITY##Voter.php',
    'scaffold/template/_actions.tpl'            => '/templates/areas/##AREALOWER##/##PREFIX##/_actions.html.twig',
    'scaffold/template/_form.tpl'                => '/templates/areas/##AREALOWER##/##PREFIX##/_form.html.twig',
    'scaffold/template/_list_item.tpl'          => '/templates/areas/##AREALOWER##/##PREFIX##/_list_item.html.twig',
    'scaffold/template/add.tpl'                  => '/templates/areas/##AREALOWER##/##PREFIX##/add.html.twig',
    'scaffold/template/edit.tpl'                => '/templates/areas/##AREALOWER##/##PREFIX##/edit.html.twig',
    'scaffold/template/list.tpl'                 => '/templates/areas/##AREALOWER##/##PREFIX##/list.html.twig',
];
```

## Propriétés protégées supplémentaires

`string $area`, `string $prefix`, `string $routePath` (en plus de `$domain`/`$entity` hérités de [`AbstractFileCreator`](AbstractFileCreator.md)).

## API

### `init(array $options = []): void`

Surdéfinit `AbstractFileCreator::init()` : appelle `parent::init($options)` (renseigne `domain`/`entity`), puis renseigne en plus `area`, `prefix`, `routePath` depuis `$options` (chaîne vide par défaut si absents).

### `replaceVars(string $value): string` *(protected)*

Remplace, dans le contenu du template ou dans le chemin cible :

| Placeholder | Remplacé par |
|---|---|
| `##DOMAIN##` | `$this->domain` |
| `##ENTITY##` | `$this->entity` |
| `##ENTITYCAMEL##` | `lcfirst($this->entity)` (ex: `book` pour variable PHP) |
| `##ENTITYLOWER##` | `strtolower($this->entity)` |
| `##AREA##` | `$this->area` |
| `##AREALOWER##` | `strtolower($this->area)` |
| `##PREFIX##` | `$this->prefix` |
| `##ROUTEPATH##` | `$this->routePath` |

## Exemple d'usage

Injecté automatiquement dans [`MakeScaffoldCommand`](../Command/MakeScaffoldCommand.md) — voici ce qui se passe en interne :

```php
$fileCreator->init([
    'domain' => 'Catalog',
    'entity' => 'Book',
    'area' => 'Admin',
    'prefix' => 'book',
    'routePath' => 'livre',
]);

if (!$fileCreator->filesExist()) {
    $fileCreator->create();
    // crée les contrôleurs, le FormType, le Voter et les templates Twig
}
```

## Voir aussi

- [Command/MakeScaffoldCommand](../Command/MakeScaffoldCommand.md) — utilisateur de ce service, avec le détail des fichiers générés.
- [AbstractFileCreator](AbstractFileCreator.md) — logique commune héritée.
