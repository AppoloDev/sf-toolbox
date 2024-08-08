<?php

namespace AppoloDev\SFToolboxBundle\UI\Organisms\Dropdown;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent(template: '@SFToolbox/ui/organisms/dropdown/dropdown_item_container.html.twig')]
class DropdownItemContainer
{
    public bool $allowDisplay;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['allowDisplay' => true]);
        $resolver->setAllowedTypes('allowDisplay', ['bool', 'null']);

        return $resolver->resolve($data);
    }
}
