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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['multiple']) {
            $builder->addModelTransformer(new StringToArrayTransformer());
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
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
        $resolver->setDefaults([
            'configuration' => [],
            'multiple' => false,
        ]);

        $resolver->setAllowedTypes('configuration', ['array']);
        $resolver->setAllowedTypes('multiple', ['bool']);
    }
}
