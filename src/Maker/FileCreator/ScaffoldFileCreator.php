<?php

namespace AppoloDev\SFToolboxBundle\Maker\FileCreator;

class ScaffoldFileCreator extends AbstractFileCreator
{
    protected string $area;
    protected string $prefix;
    protected string $routePath;

    protected array $mapping = [
        'scaffold/controller/AddController.tpl' => '/src/Http/##AREA##/Controller/##ENTITY##/Add##ENTITY##Controller.php',
        'scaffold/controller/EditController.tpl' => '/src/Http/##AREA##/Controller/##ENTITY##/Edit##ENTITY##Controller.php',
        'scaffold/controller/DeleteController.tpl' => '/src/Http/##AREA##/Controller/##ENTITY##/Delete##ENTITY##Controller.php',
        'scaffold/controller/ListController.tpl' => '/src/Http/##AREA##/Controller/##ENTITY##/List##ENTITY##Controller.php',
        'scaffold/controller/ExportController.tpl' => '/src/Http/##AREA##/Controller/##ENTITY##/Export##ENTITY##Controller.php',
        'scaffold/form/FormType.tpl' => '/src/Http/##AREA##/Form/##ENTITY##/##ENTITY##FormType.php',
        'scaffold/voter/Voter.tpl' => '/src/Http/##AREA##/Voter/##ENTITY##Voter.php',
        'scaffold/template/_actions.tpl' => '/templates/areas/##AREALOWER##/##PREFIX##/_actions.html.twig',
        'scaffold/template/_form.tpl' => '/templates/areas/##AREALOWER##/##PREFIX##/_form.html.twig',
        'scaffold/template/_list_item.tpl' => '/templates/areas/##AREALOWER##/##PREFIX##/_list_item.html.twig',
        'scaffold/template/add.tpl' => '/templates/areas/##AREALOWER##/##PREFIX##/add.html.twig',
        'scaffold/template/edit.tpl' => '/templates/areas/##AREALOWER##/##PREFIX##/edit.html.twig',
        'scaffold/template/list.tpl' => '/templates/areas/##AREALOWER##/##PREFIX##/list.html.twig',
    ];

    public function init(array $options = []): void
    {
        parent::init($options);
        $this->area = $options['area'] ?? '';
        $this->prefix = $options['prefix'] ?? '';
        $this->routePath = $options['routePath'] ?? '';
    }

    protected function replaceVars(string $value): string
    {
        return str_replace(
            ['##DOMAIN##', '##ENTITY##', '##ENTITYCAMEL##', '##ENTITYLOWER##', '##AREA##', '##AREALOWER##', '##PREFIX##', '##ROUTEPATH##'],
            [$this->domain, $this->entity, lcfirst($this->entity), strtolower($this->entity), $this->area, strtolower($this->area), $this->prefix, $this->routePath],
            $value
        );
    }
}
