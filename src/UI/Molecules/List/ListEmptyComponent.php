<?php

namespace AppoloDev\SFToolboxBundle\UI\Molecules\List;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('list_empty', template: '@SFToolbox/ui/molecules/list/list_empty.html.twig')]
class ListEmptyComponent
{
    public ?string $title = '';
    public ?string $description = '';
    public ?string $button = null;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('title', 'Aucun résultat.');
        $resolver->setAllowedTypes('title', ['string', 'null']);

        $resolver->setDefault('description', 'Essayez d\'ajouter un nouvel élément.');
        $resolver->setAllowedTypes('description', ['string', 'null']);

        $resolver->setDefault('button', null);
        $resolver->setAllowedTypes('button', ['string', 'null']);

        return $resolver->resolve($data);
    }
}
