<?php

namespace AppoloDev\SFToolboxBundle\Form\Validator\Geolocalizable;

use AppoloDev\SFToolboxBundle\Utils\GeocompleteUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GeolocalizableConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof GeolocalizableConstraint) {
            throw new UnexpectedTypeException($constraint, GeolocalizableConstraint::class);
        }

        $geoCompletedData = GeocompleteUtils::transformGeocompleteData($value);

        $errorFields = [];

        foreach ($constraint->requiredFields as $requiredField) {
            if(is_null($geoCompletedData[$requiredField] ?? null)) {
                $errorFields[] = $requiredField;
            }
        }

        if(!empty($errorFields)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('fields', join(', ', $errorFields))
                ->addViolation();
        }
    }
}
