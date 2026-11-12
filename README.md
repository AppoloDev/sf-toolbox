# Symfony Toolbox

`appolodev/sf-toolbox` — une boîte à outils privée (namespace `AppoloDev\SFToolboxBundle`) fournissant des briques réutilisables pour les projets Symfony : traits d'entités Doctrine, une couche de requêtage fluide sur les repositories, des commandes Maker de scaffolding, quelques types de formulaire, des helpers de sécurité, et de petits utilitaires.

## Installation

```bash
composer require appolodev/sf-toolbox
```

Variables d'environnement à définir dans le projet consommateur (requises par les globales Twig / le routeur par défaut de `SFToolboxExtension`) :

```
SITE_TITLE=
THEME_COLOR=
GOOGLE_MAP_API_KEY=
DEFAULT_URI=
```

Si vous utilisez `Message\EmailMessage` (voir plus bas), définissez aussi `SENDER_EMAIL` et `SENDER_NAME`.

## Intégration Claude Code

Ce repo embarque un [Skill Claude Code](https://docs.claude.com/en/docs/claude-code/skills) dans `.claude/skills/sf-toolbox/`, pour qu'un agent Claude travaillant dans un projet dépendant de ce bundle puisse consulter son API sans avoir à relire `vendor/appolodev/sf-toolbox` en entier à chaque fois.

Claude Code ne découvre automatiquement les Skills que dans le `.claude/skills/` propre à chaque projet, jamais dans `vendor/`. Pour rendre le Skill disponible dans un projet consommateur, il faut brancher une fois le script Composer fourni, dans le `composer.json` de ce projet :

```json
{
    "scripts": {
        "post-install-cmd": [
            "AppoloDev\\SFToolboxBundle\\Composer\\ScriptHandler::installClaudeSkill"
        ],
        "post-update-cmd": [
            "AppoloDev\\SFToolboxBundle\\Composer\\ScriptHandler::installClaudeSkill"
        ]
    }
}
```

(Si le projet déclare déjà d'autres scripts sous ces clés — par exemple `@auto-scripts` de Symfony Flex — ajoutez cette entrée à la liste existante plutôt que de la remplacer.)

Lancez ensuite `composer install` (ou `update`) une fois. Cela crée `.claude/skills/sf-toolbox` à la racine du projet, sous forme de lien symbolique vers `vendor/appolodev/sf-toolbox/.claude/skills/sf-toolbox` (retombe sur une simple copie si les liens symboliques ne sont pas supportés, par exemple certaines configurations Windows). Comme il s'agit d'un lien résolu depuis `vendor/`, il reflète toujours exactement la version du bundle installée dans ce projet précis — ajoutez-le au `.gitignore` du projet :

```
/.claude/skills/sf-toolbox
```

Lorsque vous modifiez l'API publique de ce bundle, mettez à jour les fichiers du Skill sous `.claude/skills/sf-toolbox/` dans le même changement — c'est la source de vérité qui sera liée dans chaque projet consommateur.

## Fonctionnalités

### Concerns d'entité Doctrine

Les traits sous `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\*` ajoutent à une entité des champs mappés Doctrine prêts à l'emploi, avec leurs accesseurs : `Identifiable` (clé primaire UUID), `Timestampable` (`createdAt`/`updatedAt`), `Sluggable`, `Publishable`, `Activable`, `Archivable`, `Blockable`, `Deletable`, `Authenticable` (forme de `UserInterface` Symfony), `Geolocalizable`. Chacun a une `*Interface` correspondante.

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Identifiable\Identifiable;
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Timestampable\Timestampable;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Book
{
    use Identifiable;
    use Timestampable;
}
```

📖 Détail complet, méthode par méthode : [docs/Domain/Entity/Concern/](docs/README.md#domain).

### Repository Criteria

Les traits sous `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\*` se composent dans un `ServiceEntityRepository` pour lui donner une API de QueryBuilder fluide et chainable :

```php
class BookRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria, WhereCriteria, JoinCriteria, SelectCriteria, GroupAndOrderCriteria;

    protected static string $alias = 'book';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
}

$repository->getQB()->eq('published', true)->order('updatedAt', 'DESC')->getResults();
```

Également disponibles (traits optionnels) : `DateCriteria`, `PublishableCriteria`, `GeolocalizableCriteria`.

📖 Détail complet : [docs/Domain/Repository/Criteria/](docs/README.md#domain), à commencer par [BuilderCriteria](docs/Domain/Repository/Criteria/BuilderCriteria.md).

### Commandes Maker

- `bin/console make:domain:entity <Domain> <Entity>` — génère une entité + son repository sous `src/Domain/<Domain>/`.
- `bin/console make:scaffold <Domain> <Entity> <Area> <prefix> <routePath>` — génère un CRUD complet (contrôleurs, type de formulaire, voter, templates Twig) sous `src/Http/<Area>/`.

📖 Détail complet : [docs/Maker/](docs/README.md#maker).

### Types de formulaire

`TomSelectType`, `EntityTomSelectType` (widgets de sélection enrichis en JS), `CardRadioType` (choix/radio thémé en cartes), `GeolocalizableType` (champ caché + payload JS de géocomplétion, mappé automatiquement sur les champs de l'entité via `GeolocalizableSubscriber`, validé via `GeolocalizableConstraint`).

📖 Détail complet : [docs/Form/](docs/README.md#form).

### Sécurité

`AbstractVoter` (helpers de vérification de rôle `canAllow()`/`canAllowAdmin()`) et `#[IsNotGranted]` (inverse de `#[IsGranted]` natif — refuse l'accès quand la vérification *réussit*).

📖 Détail complet : [docs/Security/](docs/README.md#security).

### Divers

`CsvWriter` + `CsvFileResponse` pour l'export CSV, `ZipResponse` pour le téléchargement de fichiers zippés, `Message\EmailMessage` + son handler Messenger pour l'envoi asynchrone d'emails templatés, le filtre Twig `localizedDate` de `Twig\Extension\IntlExtension`, et les classes utilitaires statiques `ArrayUtils`, `GeocompleteUtils`, `UuidUtils`.

```twig
{{ date|localizedDate('d LLLL', 'fr') }}
```

📖 Détail complet : [docs/Csv/](docs/README.md#csv--réponses-http), [docs/Response/](docs/README.md#csv--réponses-http), [docs/Message/](docs/README.md#email-twig-utilitaires), [docs/Twig/](docs/README.md#email-twig-utilitaires), [docs/Utils/](docs/README.md#email-twig-utilitaires).

## Documentation

- [`docs/`](docs/README.md) — documentation exhaustive et en français : chaque classe a sa fiche, avec description de chaque méthode et exemples d'usage.
- `.claude/skills/sf-toolbox/` — résumé plus court, en anglais, pensé pour être chargé rapidement en contexte par un agent Claude Code (voir la section [Intégration Claude Code](#intégration-claude-code) ci-dessus).
