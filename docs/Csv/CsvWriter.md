# CsvWriter

- Classe : `AppoloDev\SFToolboxBundle\Csv\CsvWriter`
- Fichier source : `src/Csv/CsvWriter.php`

## Rôle

Enveloppe simplifiée autour de `league/csv` (`League\Csv\Writer`) pour générer du contenu CSV en mémoire, avec des réglages par défaut adaptés à Excel (délimiteur `;`, BOM UTF-8). Prévu pour alimenter [Response/CsvFileResponse](../Response/CsvFileResponse.md).

## Constructeur

### `__construct()`

Initialise un `Writer` en mémoire (`Writer::createFromString()`) avec délimiteur `;` et BOM UTF-8 (`Bom::Utf8`) déjà configurés.

## API

### `setDelimiter(string $delimiter): void`

Change le délimiteur de colonnes (par défaut `;`).

```php
$csvWriter->setDelimiter(',');
```

### `setHeaders(array $headers): self`

Insère une ligne d'en-têtes en première ligne du CSV.

```php
$csvWriter->setHeaders(['Titre', 'Auteur', 'Prix']);
```

### `setRow(array $row): self`

Insère une seule ligne de données.

```php
$csvWriter->setRow(['Les Misérables', 'Victor Hugo', '12.90']);
```

### `setRows(array $rows): self`

Insère plusieurs lignes de données d'un coup (tableau de tableaux).

```php
$csvWriter->setRows([
    ['Les Misérables', 'Victor Hugo', '12.90'],
    ['Germinal', 'Émile Zola', '9.50'],
]);
```

### `getContent(): string`

Retourne le contenu CSV complet généré jusqu'ici, sous forme de chaîne (avec BOM UTF-8 en tête).

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Csv\CsvWriter;
use AppoloDev\SFToolboxBundle\Response\CsvFileResponse;

$csv = (new CsvWriter())
    ->setHeaders(['Titre', 'Auteur', 'Prix'])
    ->setRows($books->map(fn (Book $book) => [$book->getTitle(), $book->getAuthor(), $book->getPrice()]));

return new CsvFileResponse($csv->getContent(), 'livres.csv');
```

## Voir aussi

- [Response/CsvFileResponse](../Response/CsvFileResponse.md) — pour renvoyer le contenu généré comme téléchargement HTTP.
