# CsvFileResponse

- Classe : `AppoloDev\SFToolboxBundle\Response\CsvFileResponse`
- Fichier source : `src/Response/CsvFileResponse.php`
- Étend : `Symfony\Component\HttpFoundation\Response`

## Rôle

Réponse HTTP prête à l'emploi pour proposer un contenu CSV en téléchargement (`Content-Type: text/csv`, `Content-Disposition: attachment`), à retourner directement depuis un contrôleur.

## Constructeur

### `__construct(?string $csvContent = '', string $fileName = 'export.csv', int $status = 200, array $headers = [])`

- `$csvContent` : contenu CSV déjà généré (typiquement via [`CsvWriter::getContent()`](../Csv/CsvWriter.md)).
- `$fileName` : nom de fichier proposé au téléchargement (défaut `export.csv`).
- `$status` : code HTTP (défaut `200`).
- `$headers` : en-têtes HTTP additionnels.

Configure automatiquement `Content-Type: text/csv` et `Content-Disposition: attachment; filename="..."`.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Csv\CsvWriter;
use AppoloDev\SFToolboxBundle\Response\CsvFileResponse;

#[Route('/admin/books/export', name: 'admin_book_export')]
public function export(BookRepository $repository): CsvFileResponse
{
    $csv = (new CsvWriter())
        ->setHeaders(['Titre', 'Auteur'])
        ->setRows(array_map(
            fn (Book $b) => [$b->getTitle(), $b->getAuthor()],
            $repository->getQB()->getResults()
        ));

    return new CsvFileResponse($csv->getContent(), 'export-livres.csv');
}
```

## Voir aussi

- [Csv/CsvWriter](../Csv/CsvWriter.md) — génère le contenu CSV attendu par cette réponse.
- [Response/ZipResponse](ZipResponse.md) — équivalent pour un export de plusieurs fichiers zippés.
- [Maker/Command/MakeScaffoldCommand](../Maker/Command/MakeScaffoldCommand.md) — `Export<Entity>Controller` généré, point d'utilisation naturel de cette réponse.
