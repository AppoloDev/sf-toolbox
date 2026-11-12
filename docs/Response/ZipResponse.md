# ZipResponse

- Classe : `AppoloDev\SFToolboxBundle\Response\ZipResponse`
- Fichier source : `src/Response/ZipResponse.php`
- Étend : `Symfony\Component\HttpFoundation\BinaryFileResponse`

## Rôle

Réponse HTTP qui zippe à la volée une liste de fichiers et propose l'archive résultante en téléchargement. Construit un vrai fichier zip temporaire sur disque (`sys_get_temp_dir()`), puis délègue le service à `BinaryFileResponse`.

## Constructeur

### `__construct(array $files, string $zipName = 'export.zip', int $status = 200, array $headers = [], bool $public = true, string $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, bool $autoEtag = false, bool $autoLastModified = true)`

- `$files` : tableau de `Symfony\Component\HttpFoundation\File\File` à inclure dans l'archive.
- `$zipName` : nom de fichier proposé au téléchargement (défaut `export.zip`).
- `$status`, `$headers` : réglages HTTP standards.
- `$public`, `$contentDisposition`, `$autoEtag`, `$autoLastModified` : transmis tels quels au constructeur de `BinaryFileResponse`.

Lève une `Symfony\Component\HttpFoundation\File\Exception\FileException` si l'archive zip ne peut pas être créée (ex: permissions insuffisantes sur le dossier temporaire).

> ⚠️ Le fichier zip est créé dans `sys_get_temp_dir()` avec un nom généré par `uniqid()`, mais **n'est jamais supprimé automatiquement** après l'envoi de la réponse — à garder en tête sur un export très fréquent (nettoyage périodique du dossier temporaire à prévoir côté infra si besoin).

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Response\ZipResponse;
use Symfony\Component\HttpFoundation\File\File;

#[Route('/admin/documents/export', name: 'admin_documents_export')]
public function exportZip(DocumentRepository $repository): ZipResponse
{
    $files = array_map(
        fn (Document $doc) => new File($doc->getFilePath()),
        $repository->getQB()->getResults()
    );

    return new ZipResponse($files, 'documents.zip');
}
```

## Voir aussi

- [Response/CsvFileResponse](CsvFileResponse.md) — équivalent pour un export CSV unique.
- [Domain/Entity/Concern/FileUpload](../Domain/Entity/Concern/FileUpload.md) — interface typique des entités dont les fichiers sont zippés ainsi.
