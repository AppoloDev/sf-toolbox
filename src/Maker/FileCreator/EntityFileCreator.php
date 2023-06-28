<?php

namespace AppoloDev\SFToolboxBundle\Maker\FileCreator;

class EntityFileCreator extends AbstractFileCreator
{
    protected array $mapping = [
        'doctrine/Entity.tpl' => '/src/Domain/##DOMAIN##/Entity/##ENTITY##.php',
        'doctrine/Repository.tpl' => '/src/Domain/##DOMAIN##/Repository/##ENTITY##Repository.php',
    ];

    protected function replaceVars(string $value): string
    {
        return str_replace(
            ['##DOMAIN##', '##ENTITY##', '##ALIAS##'],
            [$this->domain, $this->entity, strtolower($this->entity)],
            $value
        );
    }
}