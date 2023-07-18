<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Button;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('button_link_sweetalert', template: '@SFToolbox/ui/atoms/button/button_link_sweetalert.html.twig')]
class ButtonLinkSweelAlertComponent
{
    use ButtonComponentAttribute;

    public string $link;
    public string $target ;

    public string $swalTitle;
    public string $swalText;
    public string $swalColor;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['link' => null]);
        $resolver->setRequired('link');
        $resolver->setAllowedTypes('link', 'string');

        $resolver->setDefaults(['target' => '_self']);
        $resolver->setAllowedTypes('target', ['string', 'null']);
        $resolver->setAllowedValues('target', ['_self', '_blank']);

        $resolver->setDefaults(['swalTitle' => 'Êtes-vous sure ?']);
        $resolver->setAllowedTypes('swalTitle', ['string', 'null']);

        $resolver->setDefaults(['swalText' => 'Vous êtes sur le point d’effectuer une action totalement irréversible …']);
        $resolver->setAllowedTypes('swalText', ['string', 'null']);

        $resolver->setDefaults(['swalColor' => 'red']);
        $resolver->setAllowedTypes('swalColor', ['string', 'null']);
        $resolver->setAllowedValues('swalColor', ['orange', 'red', 'green', 'blue']);

        $this->updateResolver($resolver);

        return $resolver->resolve($data);
    }
}
