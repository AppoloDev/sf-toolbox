<?php

namespace AppoloDev\SFToolboxBundle\UI\Molecules\Generic;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('breadcrumb', template: '@SFToolbox/ui/molecules/generic/breadcrumb.html.twig')]
class BreadcrumbComponent
{
    public array $items = [];

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['items' => []]);
        $resolver->setRequired('items');
        $resolver->setAllowedTypes('items', 'array');

        return $resolver->resolve($data);
    }
}
