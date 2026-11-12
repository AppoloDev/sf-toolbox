# FileUpload

- Interface : `AppoloDev\SFToolboxBundle\Domain\Entity\Concern\FileUpload\FileUploadInterface`
- Fichier source : `src/Domain/Entity/Concern/FileUpload/FileUploadInterface.php`

## Rôle

Contrat minimal pour une entité qui expose un fichier uploadé. **Contrairement aux autres concerns, il n'y a pas de trait fourni** — vous implémentez vous-même les trois méthodes, en général via une librairie d'upload (VichUploaderBundle, ou un service maison).

## API

### `getFilename(): ?string`

Doit retourner le nom du fichier stocké (tel qu'il apparaît sur le disque/stockage), ou `null` si aucun fichier n'est associé.

### `getFile(): ?File`

Doit retourner un objet `Symfony\Component\HttpFoundation\File\File` représentant le fichier physique, ou `null`.

### `getFilePath(): ?string`

Doit retourner le chemin (relatif ou public) permettant de construire une URL/lien vers le fichier, ou `null`.

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\FileUpload\FileUploadInterface;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document implements FileUploadInterface
{
    #[ORM\Column(nullable: true)]
    private ?string $filename = null;

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->filename ? new File($this->getFilePath()) : null;
    }

    public function getFilePath(): ?string
    {
        return $this->filename ? '/uploads/documents/'.$this->filename : null;
    }
}
```

Cette interface sert surtout à typer du code générique (ex: un service d'export ou un contrôleur de téléchargement) qui doit fonctionner avec n'importe quelle entité porteuse d'un fichier, sans connaître sa classe concrète :

```php
function download(FileUploadInterface $entity): BinaryFileResponse
{
    return new BinaryFileResponse($entity->getFile()->getRealPath());
}
```
