<?php

namespace AppoloDev\SFToolboxBundle\UI\Templates\Form;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('form_layout', template: '@SFToolbox/ui/templates/form/form_layout.html.twig')]
class FormLayoutComponent
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
