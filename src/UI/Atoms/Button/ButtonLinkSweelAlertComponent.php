<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Button;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('button_link_sweetalert', template: '@SFToolbox/ui/atoms/button/button_link_sweetalert.html.twig')]
class ButtonLinkSweelAlertComponent
{
    public ?string $color;
    public string $mode;
    public string $size;
    public ?bool $block;
    public ?string $label;
    public string $link;
    public string $target ;
    public ?string $tooltip;
    public ?string $icon;
    public string $iconPlacement ;
    public bool $allowDisplay;
    public string $swalTitle;
    public string $swalText;
    public string $swalColor;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(['color' => null]);
        $resolver->setAllowedTypes('color', ['string', 'null']);
        $resolver->setAllowedValues('color', ['green', 'red', 'orange', 'blue', 'indigo', 'teal', null]);

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
        $resolver->setAllowedTypes('label', ['string', 'null']);

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

        $resolver->setDefaults(['allowDisplay' => true]);
        $resolver->setAllowedTypes('allowDisplay', ['bool', 'null']);

        $resolver->setDefaults(['swalTitle' => 'Êtes-vous sure ?']);
        $resolver->setAllowedTypes('swalTitle', ['string', 'null']);

        $resolver->setDefaults(['swalText' => 'Vous êtes sur le point d’effectuer une action totalement irréversible …']);
        $resolver->setAllowedTypes('swalText', ['string', 'null']);

        $resolver->setDefaults(['swalColor' => 'red']);
        $resolver->setAllowedTypes('swalColor', ['string', 'null']);
        $resolver->setAllowedValues('swalColor', ['orange', 'red', 'green', 'blue']);


        return $resolver->resolve($data);
    }
}
