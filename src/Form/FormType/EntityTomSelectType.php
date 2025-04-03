<?php

namespace AppoloDev\SFToolboxBundle\Form\FormType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntityTomSelectType extends EntityType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        $options['configuration']['maxItems'] = $options['configuration']['maxItems'] ?? ($options['multiple'] ? null : 1);
        $view->vars['configuration'] = $options['configuration'];
    }

    public function getBlockPrefix(): string
    {
        return 'tom_select';
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'configuration' => [],
            'multiple' => false,
        ]);

        $resolver->setAllowedTypes('configuration', ['array']);
        $resolver->setAllowedTypes('multiple', ['bool']);
    }
}
