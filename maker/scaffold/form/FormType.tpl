<?php

namespace App\Http\##AREA##\Form\##ENTITY##;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ##ENTITY##FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO: Implements
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ##ENTITY##::class,
        ]);
    }
}
