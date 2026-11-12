# PublishableCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\PublishableCriteria`
- Fichier source : `src/Domain/Repository/Criteria/PublishableCriteria.php`

## Rôle

Trait **optionnel** filtrant les entités "publiées à une date donnée", à combiner avec [`BuilderCriteria`](BuilderCriteria.md). Conçu pour fonctionner avec le concern d'entité [`Publishable`](../../Entity/Concern/Publishable.md) (champs `publicationStartDate`/`publicationEndDate`), mais fonctionne avec n'importe quels champs de même forme en renommant les arguments.

## API

### `published(string $fieldFrom = 'publicationStartDate', string $fieldTo = 'publicationEndDate', ?string $customAlias = null, ?\DateTime $currentDate = null): self`

Filtre les lignes où la date de référence tombe dans l'intervalle `[$fieldFrom, $fieldTo]`, en traitant une borne `null` comme "pas de limite" de ce côté (donc un `publicationEndDate` à `null` = publié indéfiniment ; un `publicationStartDate` à `null` = publié depuis toujours).

- Sans `$currentDate` : compare sur la journée entière en cours (00:00:00 à 23:59:59) — utile pour ignorer les variations d'heure et simplement savoir "est-ce publié aujourd'hui ?".
- Avec `$currentDate` : compare exactement à cet instant précis (même valeur utilisée comme borne haute et basse).

```php
$repository->getQB()->published()->getResults();
// équivalent à :
// WHERE (publicationStartDate <= aujourd'hui OU publicationStartDate IS NULL)
//   AND (publicationEndDate >= aujourd'hui OU publicationEndDate IS NULL)
```

Avec des noms de champs personnalisés :

```php
$repository->getQB()->published(fieldFrom: 'startsAt', fieldTo: 'endsAt')->getResults();
```

Avec une date de référence précise (ex: simuler "était-ce publié le 1er janvier ?") :

```php
$repository->getQB()->published(currentDate: new \DateTime('2026-01-01 12:00:00'))->getResults();
```

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\Publishable\Publishable;
use AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\PublishableCriteria;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    use Publishable;
}

class ArticleRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria, PublishableCriteria, GroupAndOrderCriteria;

    protected static string $alias = 'article';
    // ...
}

// Articles visibles publiquement aujourd'hui, les plus récents en premier :
$articles = $articleRepository->getQB()->published()->order('publicationStartDate', 'DESC')->getResults();
```

## Voir aussi

- [Domain/Entity/Concern/Publishable](../../Entity/Concern/Publishable.md) — le concern d'entité correspondant.
- [DateCriteria](DateCriteria.md) — filtres de date plus génériques.
