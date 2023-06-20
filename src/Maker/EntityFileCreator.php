<?php

namespace AppoloDev\SFToolboxBundle\Maker;

use Symfony\Component\Filesystem\Filesystem;

class EntityFileCreator
{
    private string $domain;
    private string $entity;
    private string $domainDirectory;

    private string $entityTemplate;
    private string $repositoryTemplate;

    public function __construct(private readonly string $rootPath)
    {
        $this->entityTemplate = file_get_contents(__DIR__.'../../Resources/skeleton/Entity.tpl');
        $this->repositoryTemplate = file_get_contents(__DIR__.'../../Resources/skeleton/Repository.tpl');
    }

    public function init(string $domain, string $entity, string $domainDirectory = 'Domain'): void
    {
        $this->domain = $domain;
        $this->entity = $entity;
        $this->domainDirectory = $domainDirectory;
    }

    public function fileExist(): bool
    {
        return file_exists($this->getEntityPath()) || file_exists($this->getRepositoryPath());
    }

    public function create(): void
    {
        if (!file_exists($this->getEntityPath())) {
            $entityContent = str_replace(['__ENTITY__', '__DOMAIN__'], [$this->entity, $this->domain], $this->entityTemplate);
            $this->createFile($this->getEntityPath(), $entityContent);
        }

        if (!file_exists($this->getRepositoryPath())) {
            $repositoryContent = str_replace(['__ENTITY__', '__DOMAIN__', '__ALIAS__'], [$this->entity, $this->domain, strtolower($this->entity)], $this->repositoryTemplate);
            $this->createFile($this->getRepositoryPath(), $repositoryContent);
        }
    }

    public function createFile(string $path, string $content): void
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($path, $content);
    }

    private function getEntityPath(): string
    {
        return sprintf($this->getFilePath(), $this->domainDirectory, $this->domain, 'Entity', $this->entity);
    }

    private function getRepositoryPath(): string
    {
        return sprintf($this->getFilePath(), $this->domainDirectory, $this->domain, 'Repository', $this->entity.'Repository');
    }

    private function getFilePath(): string
    {
        return $this->rootPath.'/src/%s/%s/%s/%s.php';
    }
}