<?php

namespace AppoloDev\SFToolboxBundle\UI\Templates\List;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('table_list', template: '@SFToolbox/ui/templates/list/table_list.html.twig')]
class TableListComponent
{
    public ?string $headerTitle;
    public ?array $tableColumns;
    public PaginationInterface $pagination;
    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['headerTitle' => null]);
        $resolver->setAllowedTypes('headerTitle', ['string', 'null']);
        $resolver->setDefaults(['tableColumns' => null]);
        $resolver->setAllowedTypes('tableColumns', ['array', 'null']);
        $resolver->setRequired('pagination');
        $resolver->setAllowedTypes('pagination', [PaginationInterface::class]);

        return $resolver->resolve($data);
    }
}
