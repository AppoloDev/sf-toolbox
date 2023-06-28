<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Button;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('button', template: '@SFToolbox/ui/atoms/button/button.html.twig')]
class ButtonComponent
{
    public ?string $color;
    public string $type;
    public string $mode;
    public string $size;
    public ?bool $block;
    public ?string $label;
    public ?string $tooltip;
    public ?string $icon;
    public string $iconPlacement;
    public bool $disabled;
    public bool $allowDisplay;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['color' => null]);
        $resolver->setAllowedTypes('color', ['string', 'null']);
        $resolver->setAllowedValues('color', ['green', 'red', 'orange', 'blue', 'indigo', 'teal', null]);

        $resolver->setDefaults(['type' => 'button']);
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedValues('type', ['button', 'submit', 'reset']);

        $resolver->setDefaults(['mode' => 'solid']);
        $resolver->setAllowedTypes('mode', 'string');
        $resolver->setAllowedValues('mode', ['solid', 'outline', 'ghost', 'soft', 'link', 'white']);

        $resolver->setDefaults(['size' => 'default']);
        $resolver->setAllowedTypes('size', 'string');
        $resolver->setAllowedValues('size', ['small', 'default', 'large']);

        $resolver->setDefaults(['label' => null]);
        $resolver->setAllowedTypes('label', ['string', 'null']);

        $resolver->setDefaults(['block' => false]);
        $resolver->setAllowedTypes('block', ['bool', 'null']);

        $resolver->setDefaults(['tooltip' => null]);
        $resolver->setAllowedTypes('tooltip', ['string', 'null']);

        $resolver->setDefaults(['icon' => null]);
        $resolver->setAllowedTypes('icon', ['string', 'null']);

        $resolver->setDefaults(['iconPlacement' => 'left']);
        $resolver->setAllowedTypes('iconPlacement', ['string']);
        $resolver->setAllowedValues('iconPlacement', ['left', 'right']);

        $resolver->setDefaults(['disabled' => false]);
        $resolver->setAllowedTypes('disabled', ['bool', 'null']);

        $resolver->setDefaults(['allowDisplay' => true]);
        $resolver->setAllowedTypes('allowDisplay', ['bool', 'null']);

        return $resolver->resolve($data);
    }
}
