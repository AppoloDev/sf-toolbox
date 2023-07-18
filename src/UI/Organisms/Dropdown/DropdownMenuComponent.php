<?php

namespace AppoloDev\SFToolboxBundle\UI\Organisms\Dropdown;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('dropdown_menu', template: '@SFToolbox/ui/organisms/dropdown/dropdown_menu.html.twig')]
class DropdownMenuComponent
{
    public array $items = [];
    public ?string $button = null;
    public ?string $headerLabel = null;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('items');
        $resolver->setDefault('items', []);
        $resolver->setAllowedTypes('items', 'array');

        $resolver->setDefault('button', null);
        $resolver->setAllowedTypes('button', ['string', 'null']);

        $resolver->setDefault('headerLabel', null);
        $resolver->setAllowedTypes('headerLabel', ['string', 'null']);

        return $resolver->resolve($data);
    }
}
