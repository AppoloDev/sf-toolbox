<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Button;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('button_link', template: '@SFToolbox/ui/atoms/button/button_link.html.twig')]
class ButtonLinkComponent
{
    public string $color;
    public string $mode;
    public string $size;
    public ?bool $block;
    public string $label;
    public string $link;
    public string $target ;
    public ?string $tooltip = null;
    public ?string $icon = null;
    public string $iconPlacement ;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['color' => 'green']);
        $resolver->setAllowedTypes('color', 'string');
        $resolver->setAllowedValues('color', ['green', 'red', 'orange', 'blue', 'indigo', 'teal']);

        $resolver->setDefaults(['mode' => 'solid']);
        $resolver->setAllowedTypes('mode', 'string');
        $resolver->setAllowedValues('mode', ['solid', 'outline', 'ghost', 'soft', 'link', 'white']);

        $resolver->setDefaults(['size' => 'default']);
        $resolver->setAllowedTypes('size', 'string');
        $resolver->setAllowedValues('size', ['small', 'default', 'large']);

        $resolver->setDefaults(['link' => null]);
        $resolver->setRequired('link');
        $resolver->setAllowedTypes('link', 'string');

        $resolver->setDefaults(['label' => null]);
        $resolver->setRequired('label');
        $resolver->setAllowedTypes('label', 'string');

        $resolver->setDefaults(['target' => '_self']);
        $resolver->setAllowedTypes('target', ['string', 'null']);
        $resolver->setAllowedValues('target', ['_self', '_blank']);

        $resolver->setDefaults(['block' => false]);
        $resolver->setAllowedTypes('block', ['bool', 'null']);

        $resolver->setDefaults(['tooltip' => null]);
        $resolver->setAllowedTypes('tooltip', ['string', 'null']);

        $resolver->setDefaults(['icon' => null]);
        $resolver->setAllowedTypes('icon', ['string', 'null']);

        $resolver->setDefaults(['iconPlacement' => 'left']);
        $resolver->setAllowedTypes('iconPlacement', ['string']);
        $resolver->setAllowedValues('iconPlacement', ['left', 'right']);

        return $resolver->resolve($data);
    }
}
