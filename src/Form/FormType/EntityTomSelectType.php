<?php

namespace AppoloDev\SFToolboxBundle\Form\FormType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityTomSelectType extends EntityType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        /** @var array $configuration */
        $configuration = $options['configuration'];

        $configuration['maxItems'] = $configuration['maxItems'] ?? ($options['multiple'] ? null : 1);
        $view->vars['configuration'] = $configuration;
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
