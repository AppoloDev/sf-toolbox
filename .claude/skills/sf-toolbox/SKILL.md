---
name: sf-toolbox
description: Reference for the AppoloDev SFToolboxBundle (composer package appolodev/sf-toolbox, PHP namespace AppoloDev\SFToolboxBundle), a private Symfony bundle providing Doctrine entity concerns/traits, a repository query-builder Criteria system, custom Maker commands (make:domain:entity, make:scaffold), Symfony form types (TomSelect, EntityTomSelect, CardRadio, Geolocalizable), security helpers (AbstractVoter, #[IsNotGranted]), CSV/Zip HTTP responses, an EmailMessage Messenger flow, and a Twig Intl filter. Use whenever a project's composer.json requires appolodev/sf-toolbox and you are working with Doctrine entities/repositories, the make:domain:entity or make:scaffold commands, form types, voters, or any class under vendor/appolodev/sf-toolbox — read this instead of scanning the vendor source.
---

# SFToolboxBundle

Private Symfony toolbox bundle (`appolodev/sf-toolbox`, namespace `AppoloDev\SFToolboxBundle`), reused across many Symfony projects. This skill exists so you don't have to re-read `vendor/appolodev/sf-toolbox` in every project. Check the installed version in the project's `composer.lock` before relying on details below — this skill describes the source of truth repo, and a given project may pin an older version.

Registered via `SFToolboxBundle` (an `AbstractBundle`), config loaded from `config/services.yaml` — all classes under `src/` are autowired/autoconfigured by default except `DependencyInjection/`, `Domain/Entity/`, `Domain/Repository/` (excluded because they're base classes meant to be extended/`use`d, not services).

For an exhaustive, per-method, French-language reference with usage examples (one file per class, mirroring the `src/` namespace tree), see `docs/README.md` at the root of the `appolodev/sf-toolbox` package (`vendor/appolodev/sf-toolbox/docs/README.md` from a consuming project) — this skill is a deliberately shorter summary of the same material for quick agent lookup.

## Feature areas

| Area | What it gives you | Reference |
|---|---|---|
| Entity traits ("Concerns") | Ready-made Doctrine fields + getters/setters to `use` in entities (Identifiable, Timestampable, Sluggable, Publishable, Activable, Archivable, Blockable, Deletable, Authenticable, Geolocalizable, FileUpload) | [domain-entity-concerns.md](references/domain-entity-concerns.md) |
| Repository Criteria traits | Composable traits (`use` in a `ServiceEntityRepository`) that add fluent QueryBuilder helpers: `where`, `join`, `select`, `order/groupBy`, `date`, `published`, `around`/`bounds` (geo) | [repository-criteria.md](references/repository-criteria.md) |
| Maker commands | `make:domain:entity` and `make:scaffold` console commands that scaffold entities/repositories and full CRUD (controllers, form, voter, templates) from templates in `maker/` | [maker-commands.md](references/maker-commands.md) |
| Form types & validators | `TomSelectType`, `EntityTomSelectType`, `CardRadioType`, `GeolocalizableType` + related data transformers, form subscriber and validator | [form-types.md](references/form-types.md) |
| Security | `AbstractVoter` (role-check helpers) and `#[IsNotGranted]` attribute (inverse of `#[IsGranted]`) | [security.md](references/security.md) |
| Misc (Csv, Response, Message, Twig, Utils) | `CsvWriter`, `CsvFileResponse`, `ZipResponse`, `EmailMessage` + its Messenger handler, `IntlExtension` Twig filter, `ArrayUtils`/`GeocompleteUtils`/`UuidUtils` | [misc.md](references/misc.md) |

## Quick orientation

- **Entities**: `use` one or more `*Concern` traits from `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\*` in a project's own entity class. Each trait has a matching `*Interface` if you need to type-hint against the concern rather than the entity. See [domain-entity-concerns.md](references/domain-entity-concerns.md).
- **Repositories**: a project's `XRepository extends ServiceEntityRepository` typically does `use BuilderCriteria, WhereCriteria, JoinCriteria, SelectCriteria, GroupAndOrderCriteria;` (+ `DateCriteria`, `PublishableCriteria`, `GeolocalizableCriteria` as needed), implements `BuilderCriteriaInterface`, and declares `protected static string $alias`. Calls are chained: `$repository->getQB()->eq('status', 'active')->order('updatedAt', 'DESC')->getResults()`. See [repository-criteria.md](references/repository-criteria.md) — this is the area with the most surface, read it before writing custom repository queries.
- **Scaffolding a new domain object**: `bin/console make:domain:entity <Domain> <Entity>` creates the entity + repository under `src/Domain/<Domain>/`; `bin/console make:scaffold <Domain> <Entity> <Area> <prefix> <routePath>` generates a CRUD (controllers, form, voter, twig templates) under `src/Http/<Area>/`. See [maker-commands.md](references/maker-commands.md) for exact generated structure and naming conventions before hand-writing CRUD boilerplate that this command already produces.
- **Namespacing convention baked into the generated code**: domain/business code lives under `App\Domain\<Domain>\{Entity,Repository}`, HTTP layer under `App\Http\<Area>\{Controller,Form,Voter}`, templates under `templates/areas/<area>/<prefix>/`.

## Non-obvious gotchas

- `Sluggable::updateSlug()` only does something if the entity also defines a `getTitle()` method (checked via `method_exists`) — silently does nothing otherwise.
- `Identifiable` primary keys are `Symfony\Component\Uid\Uuid`, generated via `UuidGenerator` — not auto-increment integers. Criteria helpers (`ComplexBuilder::comparisonOperator`, `getValue()`) automatically detect UUID strings and convert them to binary/`uuid` param type, so passing a UUID string to `eq()`/`in()` etc. just works.
- `AuthenticableInterface`/`Authenticable` implement Symfony's `UserInterface` shape (`getUserIdentifier`, `getRoles`, `eraseCredentials`, etc.) but don't declare `implements UserInterface` themselves — the consuming entity must add that.
- `#[IsNotGranted]` (in `Security\Http\Attribute`) is the inverse of core Symfony's `#[IsGranted]`: it denies access when the voter/expression **is** granted. Don't confuse the two.
