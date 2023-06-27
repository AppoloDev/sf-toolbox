<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Badge;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('badge', template: '@SFToolbox/ui/atoms/badge/badge.html.twig')]
class BadgeComponent
{
    public string|int $label;
    public string $color;
    public string $rounded;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['color' => 'gray']);
        $resolver->setAllowedTypes('color', ['string', 'null']);
        $resolver->setAllowedValues('color', ['gray', 'green', 'red', 'blue', 'yellow', 'orange', 'indigo', 'teal']);

        $resolver->setDefaults(['rounded' => 'default']);
        $resolver->setAllowedTypes('rounded', ['string', 'null']);
        $resolver->setAllowedValues('rounded', ['default', 'large', 'full']);

        $resolver->setRequired('label');
        $resolver->setAllowedTypes('label', ['string', 'int']);

        return $resolver->resolve($data);
    }
}
