<?php

namespace AppoloDev\SFToolboxBundle\UI\Templates\Card;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('card_layout', template: '@SFToolbox/ui/templates/card/card_layout.html.twig')]
class CardLayoutComponent
{
    public ?string $headerTitle;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['headerTitle' => null]);
        $resolver->setAllowedTypes('headerTitle', ['string', 'null']);

        return $resolver->resolve($data);
    }
}
