<?php

namespace AppoloDev\SFToolboxBundle\UI\Atoms\Button;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('button', template: '@SFToolbox/ui/atoms/button/button.html.twig')]
class ButtonComponent
{
    use ButtonComponentAttribute;

    public string $type;
    public bool $disabled;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['type' => 'button']);
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedValues('type', ['button', 'submit', 'reset']);

        $resolver->setDefaults(['disabled' => false]);
        $resolver->setAllowedTypes('disabled', ['bool', 'null']);

        $this->updateResolver($resolver);

        return $resolver->resolve($data);
    }
}
