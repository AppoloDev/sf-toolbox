<?php

namespace AppoloDev\SFToolboxBundle\Maker\FileCreator;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class AbstractFileCreator
{
    protected string $domain;
    protected string $entity;

    protected array $mapping = [];

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        protected readonly string $rootPath
    ) {
    }

    public function init(array $options = []): void
    {
        $this->domain = $options['domain'] ?? '';
        $this->entity = $options['entity'] ?? '';
    }

    public function filesExist(): bool
    {
        foreach ($this->mapping as $filePath) {
            if (file_exists($this->getAbsoluteFilePath($filePath))) {
                return true;
            }
        }

        return false;
    }

    public function create(): void
    {
        foreach ($this->mapping as $template => $filePath) {
            $absoluteFilePath = $this->getAbsoluteFilePath($filePath);
            if (!file_exists($absoluteFilePath)) {
                $content = $this->replaceVars($this->getTemplateContent($template));
                $this->createFile($absoluteFilePath, $content);
            }
        }
    }

    public function createFile(string $path, string $content): void
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($path, $content);
    }

    protected function getAbsoluteFilePath(string $filePath): string
    {
        return $this->rootPath.$this->replaceVars($filePath);
    }

    protected function replaceVars(string $value): string
    {
        return $value;
    }

    protected function getTemplateContent(string $template): string
    {
        $content = file_get_contents(__DIR__.'../../../../maker/'.$template);

        return false !== $content ? $content : '';
    }
}
