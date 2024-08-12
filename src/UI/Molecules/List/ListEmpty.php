<?php

namespace AppoloDev\SFToolboxBundle\UI\Molecules\List;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent(template: '@SFToolbox/ui/molecules/list/list_empty.html.twig')]
class ListEmpty
{
    public ?string $title = '';
    public ?string $description = '';

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('title', 'Aucun résultat.');
        $resolver->setAllowedTypes('title', ['string', 'null']);

        $resolver->setDefault('description', 'Essayez d\'ajouter un nouvel élément.');
        $resolver->setAllowedTypes('description', ['string', 'null']);

        return $resolver->resolve($data);
    }
}
