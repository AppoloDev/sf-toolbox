<?php

namespace AppoloDev\SFToolboxBundle\UI\Templates\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent(template: '@SFToolbox/ui/templates/form/form_layout.html.twig')]
class FormLayout
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
