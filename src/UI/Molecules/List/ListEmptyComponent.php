<?php

namespace AppoloDev\SFToolboxBundle\UI\Molecules\List;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('list_empty', template: '@SFToolbox/ui/molecules/list/list_empty.html.twig')]
class ListEmptyComponent
{
    public string $text = '';
    public ?string $buttonLink = null;
    public ?string $buttonText = null;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('text', 'Essayez d\'ajouter un nouvel élément.');
        $resolver->setDefault('buttonText', 'Ajouter');
        $resolver->setDefault('buttonLink', null);
        $resolver->setAllowedTypes('text', 'string');
        $resolver->setAllowedTypes('buttonLink', ['string', 'null']);
        $resolver->setAllowedTypes('buttonText', ['string', 'null']);

        return $resolver->resolve($data);
    }
}
