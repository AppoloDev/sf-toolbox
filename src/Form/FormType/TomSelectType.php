<?php

namespace AppoloDev\SFToolboxBundle\Form\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TomSelectType extends AbstractType
{
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'tom_select';
    }

    // TODO: Option resolver pour l'attribut . Nom à définir (Options ?)
}
