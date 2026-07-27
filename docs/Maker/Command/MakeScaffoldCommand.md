# MakeScaffoldCommand

- Classe : `AppoloDev\SFToolboxBundle\Maker\Command\MakeScaffoldCommand`
- Fichier source : `src/Maker/Command/MakeScaffoldCommand.php`
- Commande console : `make:scaffold`

## Rôle

Génère un CRUD complet (contrôleurs, formulaire, voter, templates Twig) pour une entité déjà existante, dans une "zone" (`area`) applicative donnée (ex: `Admin`, `Front`), à partir des templates `maker/scaffold/*.tpl` (voir [FileCreator/ScaffoldFileCreator](../FileCreator/ScaffoldFileCreator.md)).

> **Prérequis** : l'entité `App\Domain\<Domain>\Entity\<Entity>` doit déjà exister (créée par exemple via [`make:domain:entity`](MakeDomainEntityCommand.md)) — la commande échoue avec "Invalid entity class" sinon.

## Signature

```bash
php bin/console make:scaffold [domain] [entity] [area] [prefix] [routePath]
```

- `domain` (optionnel, interactif si omis) : domaine métier de l'entité, ex. `Catalog`.
- `entity` (optionnel, interactif si omis) : nom de l'entité, ex. `Book`.
- `area` (optionnel, interactif si omis) : zone applicative où placer les fichiers HTTP, ex. `Admin`.
- `prefix` (optionnel, interactif si omis) : préfixe utilisé pour les noms de route/template, ex. `book`.
- `routePath` (optionnel, interactif si omis) : segment d'URL singulier, ex. `livre`.

## Fichiers générés

Tous sous `src/Http/<Area>/` et `templates/areas/<arealower>/<prefix>/` :

| Fichier généré | Détails |
|---|---|
| `Controller/<Entity>/List<Entity>Controller.php` | Route `<routePath>s`, nom `<prefix>_list` ; utilise `KnpPaginatorBundle` et `$repository->getQB()->querySearch($q)->order('updatedAt','DESC')->getBuilder()`. |
| `Controller/<Entity>/Add<Entity>Controller.php` | Route `<routePath>/ajouter`, nom `<prefix>_add`. |
| `Controller/<Entity>/Edit<Entity>Controller.php` | Route d'édition. |
| `Controller/<Entity>/Delete<Entity>Controller.php` | Route de suppression. |
| `Controller/<Entity>/Export<Entity>Controller.php` | Route d'export (à combiner avec [Response/CsvFileResponse](../../Response/CsvFileResponse.md) ou [Response/ZipResponse](../../Response/ZipResponse.md)). |
| `Form/<Entity>/<Entity>FormType.php` | Stub `AbstractType`, `data_class` déjà renseigné, corps de `buildForm()` à compléter (`// TODO: Implements`). |
| `Voter/<Entity>Voter.php` | `extends `[`AbstractVoter`](../../Security/Authorization/AbstractVoter.md), constantes `LIST`/`ADD`/`EDIT`/`DELETE`/`EXPORT`, `voteOnAttribute()` stub retournant `true` (**à remplacer par une vraie logique d'autorisation**). |
| `templates/areas/<arealower>/<prefix>/{list,add,edit,_form,_list_item,_actions}.html.twig` | Templates Twig. |

Tous les contrôleurs sont protégés par `#[IsGranted(<Entity>Voter::XXX)]`, et les noms de route suivent le format `<area_lower>_<prefix>_<action>` — cohérent avec les constantes du Voter généré : ne renommez jamais l'un sans l'autre.

## Exemple d'usage

```bash
php bin/console make:scaffold Catalog Book Admin book livre
```

Résultat (extrait) :

```
src/Http/Admin/Controller/Book/ListBookController.php   (route: livres, nom: book_list)
src/Http/Admin/Controller/Book/AddBookController.php     (route: livre/ajouter, nom: book_add)
src/Http/Admin/Form/Book/BookFormType.php
src/Http/Admin/Voter/BookVoter.php
templates/areas/admin/book/list.html.twig
```

Étapes suivantes typiques après génération : compléter `BookFormType::buildForm()`, la logique de `BookVoter::voteOnAttribute()`, et `BookRepository::querySearch()`.

## Voir aussi

- [MakeDomainEntityCommand](MakeDomainEntityCommand.md) — à exécuter en premier si l'entité n'existe pas encore.
- [FileCreator/ScaffoldFileCreator](../FileCreator/ScaffoldFileCreator.md) — logique de génération des fichiers.
- [Security/AbstractVoter](../../Security/Authorization/AbstractVoter.md) — classe parente du Voter généré.
