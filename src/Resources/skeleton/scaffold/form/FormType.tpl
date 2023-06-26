<?php

namespace App\Http\__CAPITALIZED_AREA__\Form\__ENTITY__;

use App\Domain\__DOMAIN__\Entity\__ENTITY__;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class __ENTITY__FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO: Implements
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => __ENTITY__::class,
        ]);
    }
}
