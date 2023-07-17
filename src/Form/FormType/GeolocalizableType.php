<?php

namespace AppoloDev\SFToolboxBundle\Form\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GeolocalizableType extends AbstractType
{
    public function getParent(): string
    {
        return TextType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'geo_localizable';
    }

    // TODO: Options resolver pour les eventuels champs a compléter ?
}
