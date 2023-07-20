<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Alert;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('alert', template: '@SFToolbox/ui/atoms/alert/alert.html.twig')]
class AlertComponent
{
    public ?string $header;
    public string $color;
    public ?string $description;
    public ?array $links;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['color' => 'green']);
        $resolver->setAllowedTypes('color', 'string');
        $resolver->setAllowedValues('color', ['green', 'red', 'orange', 'blue', 'indigo']);

        $resolver->setDefaults(['header' => null]);
        $resolver->setAllowedTypes('header', ['string', 'null']);

        $resolver->setDefaults(['description' => null]);
        $resolver->setAllowedTypes('description', ['string', 'null']);

        $resolver->setDefaults(['links' => null]);
        $resolver->setAllowedTypes('links', ['array', 'null']);

        return $resolver->resolve($data);
    }
}
