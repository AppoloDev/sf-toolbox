<?php

namespace AppoloDev\SFToolboxBundle\UI\Molecules\Card;

use AppoloDev\SFToolboxBundle\Domain\Entity\Concern\FileUpload\FileUploadInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent(template: '@SFToolbox/ui/molecules/card/file.html.twig')]
class CardFile
{
    public File|FileUploadInterface|null $file = null;
    public ?string $route = null;
    public ?string $extension = null;
    public ?string $filename = null;
    public string $icon = 'file';

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['route' => null]);
        $resolver->setDefaults(['file' => null]);
        $resolver->setAllowedTypes('file', [File::class, FileUploadInterface::class]);

        return $resolver->resolve($data);
    }

    #[PostMount]
    public function postMount(): void
    {
        if (!is_null($this->file)) {
            $this->extension = $this->file instanceof File ? $this->file->getExtension() : $this->file->getFile()?->getExtension();
            $this->filename = $this->file->getFilename();
        }

        $this->icon = match ($this->extension) {
            'csv', 'xls', 'xlsx' => 'file-excel',
            'doc', 'docx' => 'file-word',
            'png', 'jpg', 'jpeg', 'webp', 'gif' => 'file-image',
            'mp4', 'mpeg', 'avi' => 'file-video',
            'pdf' => 'file-pdf',
            default => 'file',
        };
    }
}
