# Documentation — AppoloDev\SFToolboxBundle

Documentation exhaustive du bundle `appolodev/sf-toolbox`, classe par classe, avec description de chaque méthode et exemples d'usage. Organisée en miroir de l'arborescence `src/` (un dossier par namespace, un fichier par classe — les couples trait+interface ou classe+classe compagnon étroitement liés, comme un `Constraint`/`ConstraintValidator`, sont regroupés dans un seul fichier).

> Pour un agent Claude Code travaillant dans un projet qui dépend de ce bundle, préférez le [Skill Claude Code](../.claude/skills/sf-toolbox/SKILL.md) — plus concis, pensé pour être chargé rapidement en contexte. Cette documentation `docs/` est la référence exhaustive, pensée pour un lecteur humain (ou une lecture approfondie ponctuelle par un agent).

## Domain

### Entity/Concern — traits d'entité Doctrine

| Classe | Description |
|---|---|
| [Identifiable](Domain/Entity/Concern/Identifiable.md) | Clé primaire UUID générée automatiquement. |
| [Timestampable](Domain/Entity/Concern/Timestampable.md) | Dates `createdAt`/`updatedAt` automatiques. |
| [Sluggable](Domain/Entity/Concern/Sluggable.md) | Slug généré à partir d'un champ `title`. |
| [Publishable](Domain/Entity/Concern/Publishable.md) | Fenêtre de publication (dates de début/fin). |
| [Activable](Domain/Entity/Concern/Activable.md) | Flag `enabled`. |
| [Archivable](Domain/Entity/Concern/Archivable.md) | Flag `archived`. |
| [Blockable](Domain/Entity/Concern/Blockable.md) | Flag `blocked`. |
| [Deletable](Domain/Entity/Concern/Deletable.md) | Flag `deleted` (suppression logique). |
| [Authenticable](Domain/Entity/Concern/Authenticable.md) | Champs et méthodes `UserInterface` (email, mot de passe, rôles, token de confirmation). |
| [Geolocalizable](Domain/Entity/Concern/Geolocalizable.md) | Champs d'adresse géolocalisée (adresse, ville, lat/lng). |
| [FileUpload](Domain/Entity/Concern/FileUpload.md) | Interface (sans trait) pour une entité porteuse d'un fichier. |

### Repository/Criteria — couche de requêtage fluide

| Classe | Description |
|---|---|
| [BuilderCriteria](Domain/Repository/Criteria/BuilderCriteria.md) | Trait fondamental : initialisation du QueryBuilder et méthodes terminales (`getResults`, `getSingleResult`...). **À lire en premier.** |
| [ComplexBuilder](Domain/Repository/Criteria/ComplexBuilder.md) | Constructeur d'expressions bas niveau (`andX`/`orX`/`eq`/`in`...), utilisé dans `complexQuery()`. |
| [WhereCriteria](Domain/Repository/Criteria/WhereCriteria.md) | Filtres `WHERE` simples et chainables (`eq`, `in`, `between`...). |
| [JoinCriteria](Domain/Repository/Criteria/JoinCriteria.md) | Jointures (`with`, `join`, `leftJoin`). |
| [SelectCriteria](Domain/Repository/Criteria/SelectCriteria.md) | Projections et agrégations (`select`, `countItem`, `max`, sous-requêtes...). |
| [GroupAndOrderCriteria](Domain/Repository/Criteria/GroupAndOrderCriteria.md) | Tri, regroupement, indexation (`order`, `groupBy`, `indexBy`, `random`). |
| [DateCriteria](Domain/Repository/Criteria/DateCriteria.md) | Filtres de date pratiques (`date`, `dateBetween`, `dateExpired`...). |
| [PublishableCriteria](Domain/Repository/Criteria/PublishableCriteria.md) | Filtre "publié à la date courante" (`published()`), pour le concern `Publishable`. |
| [GeolocalizableCriteria](Domain/Repository/Criteria/GeolocalizableCriteria.md) | Recherche géographique par rayon/bounding box (`around`, `bounds`). |
| [DoctrineOperator](Domain/Repository/Criteria/DoctrineOperator.md) | Enum des opérateurs de comparaison internes. |
| [Expression/Expression](Domain/Repository/Criteria/Expression/Expression.md) | Contrat `ExpressionInterface` + implémentation `StringExpr` (fragment DQL brut). |
| [Expression/Select](Domain/Repository/Criteria/Expression/Select.md) | Expressions de projection `PartialExpr`/`PartialDtoExpr`. |

## Form

### FormType

| Classe | Description |
|---|---|
| [TomSelectType](Form/FormType/TomSelectType.md) | Champ texte enrichi par le widget JS Tom Select. |
| [EntityTomSelectType](Form/FormType/EntityTomSelectType.md) | Équivalent basé sur des entités Doctrine. |
| [CardRadioType](Form/FormType/CardRadioType.md) | `ChoiceType` affiché en cartes sélectionnables. |
| [GeolocalizableType](Form/FormType/GeolocalizableType.md) | Champ caché recevant le JSON de géocomplétion. |

### DataTransformer

| Classe | Description |
|---|---|
| [StringToArrayTransformer](Form/DataTransformer/StringToArrayTransformer.md) | `array` ⇄ chaîne jointe par virgules. |
| [ArrayToStringTransformer](Form/DataTransformer/ArrayToStringTransformer.md) | `array` ⇄ chaîne, avec valeurs par défaut prioritaires. |
| [UppercaseTransformer](Form/DataTransformer/UppercaseTransformer.md) | Met en majuscules la saisie à la soumission. |

### Subscriber & Validator

| Classe | Description |
|---|---|
| [GeolocalizableSubscriber](Form/Subscriber/GeolocalizableSubscriber.md) | Répercute automatiquement les données géocomplétées sur l'entité. |
| [GeolocalizableConstraint](Form/Validator/GeolocalizableConstraint.md) | Valide que certains champs de l'adresse géocomplétée sont présents. |

## Maker

### Command

| Classe | Description |
|---|---|
| [MakeDomainEntityCommand](Maker/Command/MakeDomainEntityCommand.md) | `make:domain:entity` — scaffold entité + repository. |
| [MakeScaffoldCommand](Maker/Command/MakeScaffoldCommand.md) | `make:scaffold` — scaffold CRUD complet (contrôleurs, formulaire, voter, templates). |
| [InteractCommand](Maker/Command/InteractCommand.md) | Trait utilitaire d'interaction console partagé par les deux commandes ci-dessus. |

### FileCreator

| Classe | Description |
|---|---|
| [AbstractFileCreator](Maker/FileCreator/AbstractFileCreator.md) | Base commune des générateurs de fichiers (templates → fichiers réels). |
| [EntityFileCreator](Maker/FileCreator/EntityFileCreator.md) | Génère entité + repository. |
| [ScaffoldFileCreator](Maker/FileCreator/ScaffoldFileCreator.md) | Génère le CRUD complet. |

## Security

| Classe | Description |
|---|---|
| [AbstractVoter](Security/Authorization/AbstractVoter.md) | Base de Voter avec helpers `canAllow()`/`canAllowAdmin()`. |
| [IsNotGranted](Security/Http/IsNotGranted.md) | Attribut (+ écouteur) inverse de `#[IsGranted]` natif. |

## Csv & Réponses HTTP

| Classe | Description |
|---|---|
| [CsvWriter](Csv/CsvWriter.md) | Génération de contenu CSV (délimiteur `;`, BOM UTF-8). |
| [CsvFileResponse](Response/CsvFileResponse.md) | Réponse HTTP de téléchargement CSV. |
| [ZipResponse](Response/ZipResponse.md) | Réponse HTTP de téléchargement d'une archive zip générée à la volée. |

## Email, Twig, Utilitaires

| Classe | Description |
|---|---|
| [EmailMessage](Message/EmailMessage.md) | Message + handler Messenger pour l'envoi d'emails templatés Twig. |
| [IntlExtension](Twig/IntlExtension.md) | Filtre Twig `localizedDate`. |
| [ArrayUtils](Utils/ArrayUtils.md) | Recherche récursive de clé, aplatissement de tableau. |
| [GeocompleteUtils](Utils/GeocompleteUtils.md) | Parseur du JSON de géocomplétion. |
| [UuidUtils](Utils/UuidUtils.md) | Détection de chaîne UUID valide. |

## Câblage du bundle

| Classe | Description |
|---|---|
| [SFToolboxBundle](SFToolboxBundle.md) | Point d'entrée du bundle Symfony. |
| [SFToolboxExtension](DependencyInjection/SFToolboxExtension.md) | Extension de conteneur : chargement des services + configuration injectée dans le projet consommateur. |
| [ScriptHandler](Composer/ScriptHandler.md) | Script Composer qui lie le Skill Claude Code dans les projets consommateurs. |

## Parcours conseillés

- **Créer une nouvelle entité de A à Z** : [MakeDomainEntityCommand](Maker/Command/MakeDomainEntityCommand.md) → [Identifiable](Domain/Entity/Concern/Identifiable.md)/[Timestampable](Domain/Entity/Concern/Timestampable.md) → [BuilderCriteria](Domain/Repository/Criteria/BuilderCriteria.md) → [MakeScaffoldCommand](Maker/Command/MakeScaffoldCommand.md).
- **Écrire une requête de repository** : [BuilderCriteria](Domain/Repository/Criteria/BuilderCriteria.md) (point d'entrée) → [WhereCriteria](Domain/Repository/Criteria/WhereCriteria.md)/[ComplexBuilder](Domain/Repository/Criteria/ComplexBuilder.md) selon la complexité des filtres.
- **Ajouter une adresse géolocalisée à une entité** : [Geolocalizable](Domain/Entity/Concern/Geolocalizable.md) (entité) → [GeolocalizableType](Form/FormType/GeolocalizableType.md) (formulaire) → [GeolocalizableSubscriber](Form/Subscriber/GeolocalizableSubscriber.md) (mapping auto) → [GeolocalizableCriteria](Domain/Repository/Criteria/GeolocalizableCriteria.md) (recherche).
