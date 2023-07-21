# Symfony Toolbox

This package add some tools for developers who use Symfony

## Domain

TODO: Create documentation

## Twig

### Intl Extension

#### Configuration

Put this inside service:

````yaml
AppoloDev\SFToolbox\Twig\IntlExtension:
    tags:
        - { name: twig.extension }
````

#### Usage

``{{ date|localizeddate('d LLLL', 'fr') }}``

Formats can be found [here](https://unicode-org.github.io/icu/userguide/format_parse/datetime/)