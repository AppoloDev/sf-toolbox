<?php

namespace AppoloDev\SFToolboxBundle\Form\Subscriber;

use AppoloDev\SFToolboxBundle\Form\FormType\GeolocalizableType;
use AppoloDev\SFToolboxBundle\Utils\GeocompleteUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatableInterface;

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
                    'geocompleteData' => GeocompleteUtils::transformGeocompleteData($formData[$fieldName]),
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
                    $this->setFieldData($formData, $mappedField, $field['geocompleteData']);
                }
            }
        }
    }

    private function extractMappedFields(FormConfigInterface $config): array
    {
        return is_array($config->getOptions()['mappedFields']) ? $config->getOptions()['mappedFields'] : [];
    }

    private function setFieldData(object $formData, string $mappedField, array $geocompleteData): void
    {
        if (isset($geocompleteData[$mappedField]) && method_exists($formData, 'set'.ucfirst($mappedField))) {
            $setter = 'set'.ucfirst($mappedField);
            $formData->{$setter}($geocompleteData[$mappedField]);
        }
    }
}
