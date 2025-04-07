<?php

namespace AppoloDev\SFToolboxBundle\Utils;

class GeocompleteUtils
{
    public static function transformGeocompleteData(string $geocompleteJson): array
    {
        /** @var \stdClass $data */
        $data = json_decode($geocompleteJson);

        if (is_null($data)) {
            return [];
        }

        $locationData = [
            'formattedAddress' => $data->formattedAddress ?? '',
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
        }

        return $locationData;
    }
}
