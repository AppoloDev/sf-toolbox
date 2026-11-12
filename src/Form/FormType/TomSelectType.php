<?php

namespace AppoloDev\SFToolboxBundle\Form\FormType;

use AppoloDev\SFToolboxBundle\Form\DataTransformer\StringToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TomSelectType extends AbstractType
{
    public function getParent(): string
    {
        return TextType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new StringToArrayTransformer());
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        /** @var array $configuration */
        $configuration = $options['configuration'];
        $configuration['maxItems'] = $configuration['maxItems'] ?? ($options['multiple'] ? null : 1);
        $configuration['options'] = $options['choices'];
        $view->vars['configuration'] = $configuration;
    }

    public function getBlockPrefix(): string
    {
        return 'tom_select';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'configuration' => [],
            'multiple' => false,
            'choices' => [],
        ]);

        $resolver->setAllowedTypes('configuration', ['array']);
        $resolver->setAllowedTypes('choices', ['array']);
        $resolver->setAllowedTypes('multiple', ['bool']);
    }
}
