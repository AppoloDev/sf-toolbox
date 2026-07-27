# Maker Commands

Two custom `symfony/maker-bundle`-style console commands, `AppoloDev\SFToolboxBundle\Maker\Command\*`, backed by "file creators" (`Maker\FileCreator\*`) that copy/fill templates from the bundle's `maker/` directory (shipped alongside `src/`, resolved relative to it — not part of the PSR-4 autoload). Both commands are idempotent: they check `filesExist()` first and warn instead of overwriting.

## `make:domain:entity <domain> <entity> [mapping]`

`MakeDomainEntityCommand` + `EntityFileCreator`. Interactive if arguments are omitted.

Generates, from `maker/doctrine/*.tpl`:
- `src/Domain/<Domain>/Entity/<Entity>.php` — `use Identifiable; use Timestampable;` on a class with `#[ORM\Entity(repositoryClass: ...)]` and `#[ORM\HasLifecycleCallbacks]`.
- `src/Domain/<Domain>/Repository/<Entity>Repository.php` — `extends ServiceEntityRepository implements BuilderCriteriaInterface`, with `use BuilderCriteria, GroupAndOrderCriteria, WhereCriteria, JoinCriteria, SelectCriteria;`, `protected static string $alias = '<entitylower>'`, and a stub `querySearch(?string $queryString): self` method (`// TODO: Implements`).

After generating, it interactively confirms Doctrine ORM mapping is configured, and if not, prints the YAML to add under `doctrine.orm.mappings` in `config/packages/doctrine.yaml`:
```yaml
<Domain>:
    is_bundle: false
    type: attribute
    dir: '%kernel.project_dir%/src/Domain/<Domain>/Entity'
    prefix: 'App\Domain\<Domain>\Entity'
    alias: <Domain>
```
Then it shells out to `bin/console make:entity App\Domain\<Domain>\Entity\<Entity>` (interactive, via a TTY subprocess) so Symfony MakerBundle's own entity-field wizard runs against the freshly-created class.

**When asked to add fields to a domain entity**: run `make:entity` (core Symfony maker) against the existing class rather than hand-editing it, to stay consistent with this project's usual flow — but hand-editing is equally valid if the maker's interactive wizard isn't practical in the current context.

## `make:scaffold <domain> <entity> <area> <prefix> <routePath>`

`MakeScaffoldCommand` + `ScaffoldFileCreator`. Requires the entity class (`App\Domain\<Domain>\Entity\<Entity>`) to already exist (fails with "Invalid entity class" otherwise — run `make:domain:entity` first).

Generates a full CRUD, from `maker/scaffold/*.tpl`, all under `src/Http/<Area>/` and `templates/areas/<arealower>/<prefix>/`:

| Generated file | Notes |
|---|---|
| `Controller/<Entity>/List<Entity>Controller.php` | `#[Route('<routePath>s', name: '<prefix>_list')]`, uses `KnpPaginatorBundle`, calls `$repository->getQB()->querySearch($q)->order('updatedAt','DESC')->getBuilder()` |
| `Controller/<Entity>/Add<Entity>Controller.php` | `#[Route('<routePath>/ajouter', name: '<prefix>_add')]` |
| `Controller/<Entity>/Edit<Entity>Controller.php` | edit route |
| `Controller/<Entity>/Delete<Entity>Controller.php` | delete route |
| `Controller/<Entity>/Export<Entity>Controller.php` | export route (pairs well with `CsvFileResponse`/`ZipResponse`, see [misc.md](misc.md)) |
| `Form/<Entity>/<Entity>FormType.php` | stub `AbstractType`, `data_class` preset, `buildForm` left as `// TODO: Implements` |
| `Voter/<Entity>Voter.php` | `extends AbstractVoter` (see [security.md](security.md)), constants `LIST`/`ADD`/`EDIT`/`DELETE`/`EXPORT` used as the route names, `voteOnAttribute` stubbed to `return true` — **fill this in**, it is not a real access-control decision by default |
| `templates/areas/<arealower>/<prefix>/{list,add,edit,_form,_list_item,_actions}.html.twig` | Twig templates |

All controllers are `#[IsGranted(<Entity>Voter::XXX)]`-protected and route names follow `<area_lower>_<prefix>_<action>` (list/add/edit/delete/export) — matching the Voter's constants exactly, so don't rename one without the other.

**When asked to scaffold CRUD for a new entity/area**: prefer running this command over hand-writing controllers/forms/voters/templates, since it produces the project's established structure and naming; then fill in the `// TODO` stubs (`querySearch`, `FormType::buildForm`, `Voter::voteOnAttribute`).
