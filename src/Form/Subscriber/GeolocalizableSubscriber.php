<?php

namespace AppoloDev\SFToolboxBundle\Form\Subscriber;

use AppoloDev\SFToolboxBundle\Form\FormType\GeolocalizableType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;

class GeolocalizableSubscriber implements EventSubscriberInterface
{
    private array $geolocalizableFields = [];

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'processGeolocalizableFieldsAfterSubmit',
            FormEvents::PRE_SUBMIT => 'processGeolocalizableFieldsBeforeSubmit',
        ];
    }

    public function processGeolocalizableFieldsBeforeSubmit(PreSubmitEvent $event): void
    {
        /** @var array $formData */
        $formData = $event->getData();
        /** @var Form $form */
        $form = $event->getForm();

        foreach ($form->getIterator() as $fieldName => $field) {
            $fieldType = get_class($field->getConfig()->getType()->getInnerType());

            if (GeolocalizableType::class === $fieldType && isset($formData[$fieldName])) {
                $this->geolocalizableFields[$fieldName] = [
                    'mappedFields' => $this->extractMappedFields($field->getConfig()),
                    'requiredFields' => $this->extractRequiredFields($field->getConfig()),
                    'geocompleteData' => $this->transformGeocompleteData($formData[$fieldName]),
                ];
            }
        }
    }

    public function processGeolocalizableFieldsAfterSubmit(PostSubmitEvent $event): void
    {
        /** @var object $formData */
        $formData = $event->getData();

        foreach ($this->geolocalizableFields as $fieldName => $field) {
            if (isset($field['mappedFields'], $field['geocompleteData'])) {
                foreach ($field['mappedFields'] as $mappedField) {
                    if(in_array($mappedField, $field['requiredFields']) && is_null($field['geocompleteData'][$mappedField] ?? null)) {
                        $event->getForm()->get($fieldName)->addError(new FormError($mappedField . ' is required'));
                    }

                    $this->setFieldData($formData, $mappedField, $field['geocompleteData']);
                }
            }
        }
    }

    private function extractRequiredFields(FormConfigInterface $config): array
    {
        return is_array($config->getOptions()['requiredFields']) ? $config->getOptions()['requiredFields'] : [];
    }

    private function extractMappedFields(FormConfigInterface $config): array
    {
        return is_array($config->getOptions()['mappedFields']) ? $config->getOptions()['mappedFields'] : [];
    }

    private function transformGeocompleteData(string $geocompleteJson): array
    {
        /** @var \stdClass $data */
        $data = json_decode($geocompleteJson);

        if (is_null($data)) {
            return [];
        }

        $locationData = [
            'formattedAddress' => $data->formatted_address ?? '',
            'address' => '',
            'lat' => $data->location->lat ?? 0,
            'lng' => $data->location->lng ?? 0,
        ];

        foreach ($data->addressComponents as $component) {
            if (in_array('locality', $component->types)) {
                $locationData['city'] = $component->longText;
            }

            if (in_array('postal_code', $component->types)) {
                $locationData['zipCode'] = $component->longText;
            }

            if (in_array('country', $component->types)) {
                $locationData['country'] = $component->longText;
            }

            if (in_array('street_number', $component->types)) {
                $locationData['address'] .= $component->longText;
            }

            if (in_array('route', $component->types)) {
                $locationData['address'] .= ' '.$component->longText;
            }
        }

        return $locationData;
    }

    private function setFieldData(object $formData, string $mappedField, array $geocompleteData): void
    {
        if (isset($geocompleteData[$mappedField]) && method_exists($formData, 'set'.ucfirst($mappedField))) {
            $setter = 'set'.ucfirst($mappedField);
            $formData->{$setter}($geocompleteData[$mappedField]);
        }
    }
}
