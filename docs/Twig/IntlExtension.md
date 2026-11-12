# IntlExtension

- Classe : `AppoloDev\SFToolboxBundle\Twig\Extension\IntlExtension`
- Fichier source : `src/Twig/Extension/IntlExtension.php`
- Étend : `Twig\Extension\AbstractExtension`

## Rôle

Extension Twig ajoutant un filtre de formatage de date localisée, basé sur l'extension PHP `intl` (`\IntlDateFormatter`). **Enregistrée automatiquement** (autoconfiguration Twig) — aucune déclaration manuelle nécessaire dans un projet consommateur.

## API

### `getFilters(): array`

Déclare le filtre Twig `localizedDate` (méthode Twig standard, appelée automatiquement par le moteur de rendu).

### `localizedDate(\DateTime $date, ?string $format = null, string $locale = 'fr-FR'): string`

Formate `$date` selon un format ICU (`$format`), dans la locale `$locale`, avec un fuseau horaire **fixé à `Europe/Paris`** (non paramétrable). Le style de formatage est toujours `FULL`/`FULL` avant application du pattern `$format`. Le résultat passe par `ucwords()` (première lettre de chaque mot en majuscule).

- `$date` : la date à formater (type `\DateTime`, pas `\DateTimeImmutable`).
- `$format` : pattern ICU (voir la [doc ICU date/time](https://unicode-org.github.io/icu/userguide/format_parse/datetime/)), ex. `'d LLLL'` pour "27 juillet".
- `$locale` : locale ICU, ex. `'fr'`, `'fr-FR'`, `'en-US'` (défaut `'fr-FR'`).

## Exemple d'usage complet

```twig
{{ someDate|localizedDate('d LLLL', 'fr') }}
{# "27 Juillet" #}

{{ someDate|localizedDate('EEEE d MMMM yyyy', 'fr') }}
{# "Lundi 27 Juillet 2026" #}

{{ someDate|localizedDate('MMMM d, yyyy', 'en') }}
{# "July 27, 2026" #}
```

En PHP, si besoin d'appeler le filtre directement (rare, en général réservé aux templates Twig) :

```php
use AppoloDev\SFToolboxBundle\Twig\Extension\IntlExtension;

(new IntlExtension())->localizedDate(new \DateTime(), 'd LLLL', 'fr');
```
