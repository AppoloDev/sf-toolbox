<?php

namespace AppoloDev\SFToolboxBundle\Maker\FileCreator;

class EntityFileCreator extends AbstractFileCreator
{
    protected array $mapping = [
        'doctrine/Entity.tpl' => '/src/Domain/__DOMAIN__/Entity/__ENTITY__.php',
        'doctrine/Repository.tpl' => '/src/Domain/__DOMAIN__/Repository/__ENTITY__Repository.php',
    ];

    protected function replaceVars(string $value): string
    {
        // TODO: Refactor
        return str_replace(
            ['__ENTITY__', '__DOMAIN__', '__ALIAS__'],
            [$this->entity, $this->domain, strtolower($this->entity)],
            $value
        );
    }
}