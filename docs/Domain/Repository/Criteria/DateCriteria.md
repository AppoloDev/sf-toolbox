# DateCriteria

- Trait : `AppoloDev\SFToolboxBundle\Domain\Repository\Criteria\DateCriteria`
- Fichier source : `src/Domain/Repository/Criteria/DateCriteria.php`

## Rôle

Trait **optionnel** apportant des filtres de date pratiques, à combiner avec [`BuilderCriteria`](BuilderCriteria.md) (n'est pas inclus par défaut dans le repository généré par `make:domain:entity` — à ajouter manuellement avec `use DateCriteria;` si besoin).

## API

### `date(string $field, \DateTimeInterface $date, ?string $customAlias = null): self`

Filtre sur toute une journée calendaire (de `00:00:00` à `23:59:59`), quelle que soit l'heure de `$date` fournie. Convertit une éventuelle instance `\DateTime` mutable en `\DateTimeImmutable` avant de calculer les bornes.

```php
$repository->getQB()->date('createdAt', new \DateTime('2026-07-27'))->getResults();
// équivalent à : createdAt BETWEEN '2026-07-27 00:00:00' AND '2026-07-27 23:59:59'
```

### `dateBetween(string $field, \DateTimeInterface $from, \DateTimeInterface $to, ?string $customAlias = null): self`

Filtre sur un intervalle de dates précis (heures incluses, pas de recalage automatique sur des journées complètes contrairement à `date()`).

```php
$repository->getQB()->dateBetween('createdAt', new \DateTime('-7 days'), new \DateTime())->getResults();
```

### `dateNotExpired(string $field, ?\DateTimeInterface $customDate = null, ?string $customAlias = null): self`

Filtre les lignes dont `$field >= $customDate` (par défaut, la date/heure courante) — "pas encore expiré".

```php
$repository->getQB()->dateNotExpired('validUntil')->getResults();
```

### `dateExpired(string $field, ?\DateTimeInterface $customDate = null, ?string $customAlias = null): self`

Filtre les lignes dont `$field <= $customDate` (par défaut, la date/heure courante) — "déjà expiré".

```php
$repository->getQB()->dateExpired('validUntil')->getResults();
```

## Exemple d'usage complet

```php
class SubscriptionRepository extends ServiceEntityRepository implements BuilderCriteriaInterface
{
    use BuilderCriteria, DateCriteria, WhereCriteria;

    protected static string $alias = 'subscription';
    // ...
}

// Abonnements créés aujourd'hui et pas encore expirés :
$active = $subscriptionRepository
    ->getQB()
    ->date('createdAt', new \DateTime())
    ->dateNotExpired('expiresAt')
    ->getResults();
```

## Voir aussi

- [Domain/Entity/Concern/Timestampable](../../Entity/Concern/Timestampable.md) — champs `createdAt`/`updatedAt` souvent utilisés avec ce trait.
- [PublishableCriteria](PublishableCriteria.md) — filtre spécialisé pour une fenêtre de publication (`publicationStartDate`/`publicationEndDate`).
