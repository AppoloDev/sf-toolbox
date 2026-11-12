<?php

namespace AppoloDev\SFToolboxBundle\Form\Validator\Geolocalizable;

use Symfony\Component\Validator\Constraint;

class GeolocalizableConstraint extends Constraint
{
    public string $message = 'This field is incomplete';
    public array $requiredFields = [];

    public function __construct(array $options)
    {
        parent::__construct($options);

        if (isset($options['requiredFields'])) {
            $this->requiredFields = $options['requiredFields'];
        }
    }

    public function getRequiredOptions(): array
    {
        return ['requiredFields'];
    }
}
