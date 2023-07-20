<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Badge;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('badge_yesno', template: '@SFToolbox/ui/atoms/badge/badge_yesno.html.twig')]
class BadgeYesNoComponent
{
    public string $color;
    public string $rounded;
    public bool $value;
    public string $labelTrue;
    public string $labelFalse;
    public ?string $label;

    #[PostMount]
    public function postMount(array $data): array
    {
        $this->color = true === $this->value ? 'green' : 'red';
        $this->label = true === $this->value ? $this->labelTrue : $this->labelFalse;

        return $data;
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['color' => 'red']);
        $resolver->setAllowedTypes('color', ['string', 'null']);
        $resolver->setAllowedValues('color', ['green', 'red']);

        $resolver->setDefaults(['rounded' => 'default']);
        $resolver->setAllowedTypes('rounded', ['string', 'null']);
        $resolver->setAllowedValues('rounded', ['default', 'large', 'full']);

        $resolver->setDefaults(['labelTrue' => 'Oui']);
        $resolver->setRequired('labelTrue');
        $resolver->setAllowedTypes('labelTrue', ['string']);

        $resolver->setDefaults(['labelFalse' => 'Non']);
        $resolver->setRequired('labelFalse');
        $resolver->setAllowedTypes('labelFalse', ['string']);

        $resolver->setDefaults(['label' => null]);
        $resolver->setAllowedTypes('label', ['string', 'null']);

        $resolver->setRequired('value');
        $resolver->setAllowedTypes('value', ['bool']);

        return $resolver->resolve($data);
    }
}
