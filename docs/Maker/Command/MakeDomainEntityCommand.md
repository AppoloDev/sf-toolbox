# MakeDomainEntityCommand

- Classe : `AppoloDev\SFToolboxBundle\Maker\Command\MakeDomainEntityCommand`
- Fichier source : `src/Maker/Command/MakeDomainEntityCommand.php`
- Commande console : `make:domain:entity`

## Rôle

Génère une entité Doctrine et son repository dans l'arborescence `src/Domain/<Domain>/`, à partir des templates `maker/doctrine/Entity.tpl` et `Repository.tpl` (voir [FileCreator/EntityFileCreator](../FileCreator/EntityFileCreator.md)), puis enchaîne sur la commande native `make:entity` de Symfony MakerBundle pour l'assistant interactif d'ajout de champs.

## Signature

```bash
php bin/console make:domain:entity [domain] [entity] [mapping]
```

- `domain` (optionnel, demandé interactivement si omis) : nom du domaine métier, ex. `Catalog`.
- `entity` (optionnel, demandé interactivement si omis) : nom de l'entité, ex. `Book`.
- `mapping` (optionnel) : confirmation interne que le mapping Doctrine a été configuré — généralement laissé à la commande elle-même (voir plus bas).

## Fichiers générés

- `src/Domain/<Domain>/Entity/<Entity>.php` — utilise les traits [`Identifiable`](../../Domain/Entity/Concern/Identifiable.md) et [`Timestampable`](../../Domain/Entity/Concern/Timestampable.md), avec `#[ORM\Entity(repositoryClass: ...)]` et `#[ORM\HasLifecycleCallbacks]`.
- `src/Domain/<Domain>/Repository/<Entity>Repository.php` — `extends ServiceEntityRepository implements BuilderCriteriaInterface`, utilise [`BuilderCriteria`](../../Domain/Repository/Criteria/BuilderCriteria.md), [`GroupAndOrderCriteria`](../../Domain/Repository/Criteria/GroupAndOrderCriteria.md), [`WhereCriteria`](../../Domain/Repository/Criteria/WhereCriteria.md), [`JoinCriteria`](../../Domain/Repository/Criteria/JoinCriteria.md), [`SelectCriteria`](../../Domain/Repository/Criteria/SelectCriteria.md), avec `protected static string $alias` déjà renseigné, et une méthode stub `querySearch(?string $queryString): self` à compléter vous-même.

## Déroulement interactif

1. Si `domain`/`entity` ne sont pas fournis en argument, la commande les demande via `interact()`.
2. Vérifie si les fichiers existent déjà (avertit sans écraser si c'est le cas).
3. Génère les deux fichiers.
4. Demande confirmation que le mapping Doctrine ORM est configuré (boucle bloquante tant que non confirmé) ; si non confirmé, affiche le YAML à ajouter dans `config/packages/doctrine.yaml` sous `doctrine.orm.mappings` :
   ```yaml
   <Domain>:
       is_bundle: false
       type: attribute
       dir: '%kernel.project_dir%/src/Domain/<Domain>/Entity'
       prefix: 'App\Domain\<Domain>\Entity'
       alias: <Domain>
   ```
5. Une fois confirmé, lance `bin/console make:entity App\Domain\<Domain>\Entity\<Entity>` dans un sous-processus interactif (TTY), pour ajouter des champs à l'entité via l'assistant natif de Symfony MakerBundle.

## Exemple d'usage

```bash
php bin/console make:domain:entity Catalog Book
```

Résultat :

```
src/Domain/Catalog/Entity/Book.php
src/Domain/Catalog/Repository/BookRepository.php
```

Puis, une fois le mapping Doctrine confirmé, l'assistant `make:entity` natif de Symfony prend le relais pour ajouter les champs (titre, prix, etc.) de manière interactive.

## Voir aussi

- [FileCreator/EntityFileCreator](../FileCreator/EntityFileCreator.md) — logique de génération des fichiers.
- [MakeScaffoldCommand](MakeScaffoldCommand.md) — pour générer le CRUD complet une fois l'entité créée.
