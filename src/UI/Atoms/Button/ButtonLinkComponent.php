<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Button;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('button_link', template: '@SFToolbox/ui/atoms/button/button_link.html.twig')]
class ButtonLinkComponent
{
    use ButtonComponentAttribute;

    public string $link;
    public string $target;

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

        $this->updateResolver($resolver);

        return $resolver->resolve($data);
    }
}
