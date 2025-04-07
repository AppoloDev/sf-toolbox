<?php

namespace AppoloDev\SFToolboxBundle\Form\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeolocalizableType extends AbstractType
{
    public function getParent(): string
    {
        return HiddenType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'geo_localizable';
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        $view->vars['autocompleteOptions'] = $options['autocompleteOptions'];
        $view->vars['placeholder'] = $options['placeholder'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'autocompleteOptions' => [],
            'placeholder' => '',
            'mappedFields' => []
        ]);

        $resolver->setAllowedTypes('mappedFields', ['array']);
        $resolver->setAllowedTypes('placeholder', ['string']);
    }
}
