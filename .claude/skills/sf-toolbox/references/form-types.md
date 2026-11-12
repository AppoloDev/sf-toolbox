# Form Types, Transformers, Subscriber & Validator

Namespace: `AppoloDev\SFToolboxBundle\Form\*`.

## `TomSelectType` (`Form\FormType\TomSelectType`)

Extends `TextType`, block prefix `tom_select`. A text-input-backed select meant to be enhanced client-side by the [Tom Select](https://tom-select.js.org/) JS widget (this bundle only wires the server-side form type + view variable; the JS/Stimulus controller lives in the consuming project's assets).

Options: `configuration` (array, passed through to `view.vars['configuration']`, merged with `maxItems` — defaults to `1` unless `multiple: true`, and `choices`), `multiple` (bool), `choices` (array, becomes `configuration['options']`). When `multiple: true`, a `StringToArrayTransformer` model transformer is added (comma-joined string ⇄ array).

## `EntityTomSelectType` (`Form\FormType\EntityTomSelectType`)

Extends core `EntityType` (Doctrine-bridge), same `tom_select` block prefix and `configuration`/`multiple` option pattern as above, but backed by a real entity choice list instead of a plain string. Use this one when the field should reference Doctrine entities; use plain `TomSelectType` for free-text/tag-like multi-value strings.

## `CardRadioType` (`Form\FormType\CardRadioType`)

Extends `ChoiceType`, block prefix `card_radio` — a `ChoiceType` themed as selectable cards (the actual `card_radio` Twig block/theme lives in the consuming project). No extra PHP options beyond `ChoiceType`'s own.

## `GeolocalizableType` (`Form\FormType\GeolocalizableType`)

Extends `HiddenType`, block prefix `geo_localizable`. Pairs with the `Geolocalizable` entity concern ([domain-entity-concerns.md](domain-entity-concerns.md)) and a JS geocomplete widget (e.g. Google Places) that writes a JSON blob into this hidden field.

Options: `placeholder` (string), `requestOptions` (array, passed to the JS widget via the view), `mappedFields` (array — which sub-fields of the geocomplete JSON to copy onto the entity, consumed by `GeolocalizableSubscriber`), `requiredFields` (array — used by `GeolocalizableConstraint`, see below). `error_bubbling` defaults to `false`.

### `GeolocalizableSubscriber` (`Form\Subscriber\GeolocalizableSubscriber`)

An `EventSubscriberInterface` (autowired/autoconfigured, tagged as `kernel.event_subscriber` automatically — no manual registration needed) listening to `FormEvents::PRE_SUBMIT`/`POST_SUBMIT` on **every form**. On pre-submit it scans the form for any field whose type is `GeolocalizableType`, parses the submitted JSON via `GeocompleteUtils::transformGeocompleteData()` ([misc.md](misc.md)), and on post-submit calls the matching setter (`set<MappedField>`) on the *parent* form's underlying data object for each field listed in that field's `mappedFields` option. In practice: give a `GeolocalizableType` field `mappedFields: ['formattedAddress', 'lat', 'lng', 'city', 'zipCode']` and the sibling entity properties get populated automatically from the geocomplete payload — no manual mapping code needed in the FormType or controller.

### `GeolocalizableConstraint` / `GeolocalizableConstraintValidator` (`Form\Validator\Geolocalizable\*`)

A `Constraint`/`ConstraintValidator` pair, applied to a `GeolocalizableType`-backed value: parses the submitted JSON via `GeocompleteUtils::transformGeocompleteData()` and adds a violation (default message `"This field is incomplete"`) if any field named in the **required** constraint option `requiredFields` is missing/null in the parsed data. Construct with `new GeolocalizableConstraint(['requiredFields' => ['city', 'zipCode']])` (`requiredFields` has no default — `getRequiredOptions()` enforces it).

## Data Transformers (`Form\DataTransformer\*`)

Standalone `DataTransformerInterface` implementations, usable on any form field via `$builder->get('field')->addModelTransformer(...)`/`addViewTransformer(...)`:
- `StringToArrayTransformer` — comma-joined string ⇄ `array` (used internally by `TomSelectType` when `multiple: true`).
- `ArrayToStringTransformer(array $defaultValues, bool $multiple = false)` — array ⇄ string; on transform, if any of `$defaultValues` is present in the array it's returned as-is (priority match), otherwise the array is joined with `,` (`multiple: true`) or just the last element is kept (`multiple: false`).
- `UppercaseTransformer` — passthrough transform, `strtoupper()` on reverse-transform (i.e. uppercases user input on submit, not on display).
