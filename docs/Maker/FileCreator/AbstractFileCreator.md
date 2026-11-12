# AbstractFileCreator

- Classe : `AppoloDev\SFToolboxBundle\Maker\FileCreator\AbstractFileCreator`
- Fichier source : `src/Maker/FileCreator/AbstractFileCreator.php`

## Rôle

Classe de base des générateurs de fichiers utilisés par les commandes Maker ([`MakeDomainEntityCommand`](../Command/MakeDomainEntityCommand.md), [`MakeScaffoldCommand`](../Command/MakeScaffoldCommand.md)). Charge un template depuis le dossier `maker/` du bundle, remplace des placeholders (`##XXX##`), et écrit le résultat sur disque. Voir les classes concrètes [EntityFileCreator](EntityFileCreator.md) et [ScaffoldFileCreator](ScaffoldFileCreator.md) pour les mappings réels utilisés en pratique.

## Constructeur

### `__construct(string $rootPath)`

`$rootPath` est injecté automatiquement (`#[Autowire('%kernel.project_dir%')]`) — c'est la racine du projet consommateur (pas du bundle).

## API

### `init(array $options = []): void`

Initialise `$this->domain` et `$this->entity` à partir de `$options['domain']`/`$options['entity']` (chaîne vide par défaut si absents). **Les classes filles surchargent cette méthode** pour initialiser des propriétés supplémentaires (voir [ScaffoldFileCreator::init()](ScaffoldFileCreator.md)), en appelant `parent::init($options)` en premier.

### `filesExist(): bool`

Retourne `true` si **au moins un** des fichiers listés dans `$this->mapping` (propriété définie par la classe fille, voir ci-dessous) existe déjà sur le disque. Utilisé par les commandes pour éviter d'écraser des fichiers existants.

### `create(): void`

Pour chaque entrée `template => filePath` de `$this->mapping` : si le fichier cible n'existe pas déjà, charge le contenu du template, y applique `replaceVars()`, et écrit le résultat via `createFile()`. Les fichiers déjà existants sont ignorés silencieusement (pas d'écrasement, même partiel).

### `createFile(string $path, string $content): void`

Écrit `$content` dans `$path` en utilisant `Symfony\Component\Filesystem\Filesystem::dumpFile()` (crée les dossiers parents nécessaires automatiquement).

### `getAbsoluteFilePath(string $filePath): string` *(protected)*

Concatène `$this->rootPath` avec `$filePath` **après** application de `replaceVars()` sur `$filePath` lui-même (donc les placeholders dans les *chemins* de fichiers, comme `##DOMAIN##`, sont eux aussi remplacés — voir [EntityFileCreator](EntityFileCreator.md)/[ScaffoldFileCreator](ScaffoldFileCreator.md)).

### `replaceVars(string $value): string` *(protected)*

Ne fait rien dans la classe de base (retourne `$value` tel quel) — **à surdéfinir dans chaque classe fille** pour remplacer les placeholders spécifiques (`##ENTITY##`, `##DOMAIN##`, etc.) par les valeurs réelles.

### `getTemplateContent(string $template): string` *(protected)*

Charge le contenu brut d'un fichier sous `maker/` (relatif à `src/Maker/FileCreator/`, donc `maker/<template>` résolu depuis `__DIR__.'/../../../../maker/'`). Retourne une chaîne vide si le fichier est introuvable.

## Propriétés protégées

- `string $domain`, `string $entity` : renseignées par `init()`.
- `array $mapping = []` : à définir dans chaque classe fille — associe un chemin de template (relatif à `maker/`) à un chemin de fichier cible (relatif à la racine du projet, avec placeholders).

## Voir aussi

- [EntityFileCreator](EntityFileCreator.md), [ScaffoldFileCreator](ScaffoldFileCreator.md) — implémentations concrètes.
