<?php

namespace AppoloDev\SFToolboxBundle\UI\Molecules\Form;

use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('form_submit', template: '@SFToolbox/ui/molecules/form/form_submit.html.twig')]
class FormSubmitComponent
{
    public ?FormView $form = null;
    public string $submitField = '';
    public ?string $deleteButtonLink = null;
    public string $deleteSwalTitle;
    public string $deleteSwalText;
    public string $deleteSwalColor;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('form');
        $resolver->setAllowedTypes('form', FormView::class);

        $resolver->setDefault('submitField', 'submit');
        $resolver->setAllowedTypes('submitField', 'string');

        $resolver->setDefault('deleteButtonLink', null);
        $resolver->setAllowedTypes('deleteButtonLink', ['string', 'null']);

        $resolver->setDefaults(['deleteSwalTitle' => 'Êtes-vous sure ?']);
        $resolver->setAllowedTypes('deleteSwalTitle', ['string', 'null']);

        $resolver->setDefaults(['deleteSwalText' => 'Vous êtes sur le point d’effectuer une action totalement irréversible …']);
        $resolver->setAllowedTypes('deleteSwalText', ['string', 'null']);

        $resolver->setDefaults(['deleteSwalColor' => 'red']);
        $resolver->setAllowedTypes('deleteSwalColor', ['string', 'null']);
        $resolver->setAllowedValues('deleteSwalColor', ['orange', 'red', 'green', 'blue']);

        return $resolver->resolve($data);
    }
}
