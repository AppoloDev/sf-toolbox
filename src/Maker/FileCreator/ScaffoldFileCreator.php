<?php

namespace AppoloDev\SFToolboxBundle\Maker\FileCreator;

class ScaffoldFileCreator extends AbstractFileCreator
{
    protected string $area;
    protected string $prefix;
    protected string $routePath;

    protected array $mapping = [
        'scaffold/controller/AddController.tpl' => '/src/Http/__CAPITALIZED_AREA__/Controller/__ENTITY__/Add__ENTITY__Controller.php',
        'scaffold/controller/EditController.tpl' => '/src/Http/__CAPITALIZED_AREA__/Controller/__ENTITY__/Edit__ENTITY__Controller.php',
        'scaffold/controller/DeleteController.tpl' => '/src/Http/__CAPITALIZED_AREA__/Controller/__ENTITY__/Delete__ENTITY__Controller.php',
        'scaffold/controller/ListController.tpl' => '/src/Http/__CAPITALIZED_AREA__/Controller/__ENTITY__/List__ENTITY__Controller.php',
        'scaffold/controller/ExportController.tpl' => '/src/Http/__CAPITALIZED_AREA__/Controller/__ENTITY__/Export__ENTITY__Controller.php',
        'scaffold/form/FormType.tpl' => '/src/Http/__CAPITALIZED_AREA__/Form/__ENTITY__/__ENTITY__FormType.php',
        'scaffold/voter/Voter.tpl' => '/src/Http/__CAPITALIZED_AREA__/Voter/__ENTITY__Voter.php',
        'scaffold/template/_actions.tpl' => '/templates/areas/__LOWER_AREA__/__PREFIX__/_actions.html.twig',
        'scaffold/template/_form.tpl' => '/templates/areas/__LOWER_AREA__/__PREFIX__/_form.html.twig',
        'scaffold/template/_list_item.tpl' => '/templates/areas/__LOWER_AREA__/__PREFIX__/_list_item.html.twig',
        'scaffold/template/add.tpl' => '/templates/areas/__LOWER_AREA__/__PREFIX__/add.html.twig',
        'scaffold/template/edit.tpl' => '/templates/areas/__LOWER_AREA__/__PREFIX__/edit.html.twig',
        'scaffold/template/list.tpl' => '/templates/areas/__LOWER_AREA__/__PREFIX__/list.html.twig',
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
            ['__ENTITY__', '__DOMAIN__', '__CAPITALIZED_AREA__', '__LOWER_AREA__', '__PREFIX__', '__ROUTE_PATH__'],
            [$this->entity, $this->domain, $this->area, strtolower($this->area), $this->prefix, $this->routePath],
            $value
        );
    }
}