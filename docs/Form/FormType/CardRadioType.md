# CardRadioType

- Classe : `AppoloDev\SFToolboxBundle\Form\FormType\CardRadioType`
- Fichier source : `src/Form/FormType/CardRadioType.php`

## Rôle

Type de champ basé sur `ChoiceType`, destiné à être affiché sous forme de "cartes" sélectionnables (radio boutons stylisés en cartes) plutôt qu'une liste déroulante ou des radios classiques. Le thème Twig réel (bloc `card_radio`) doit être défini côté projet consommateur — cette classe ne fait que déclarer le bloc et hériter du comportement de `ChoiceType`.

## Options

Aucune option spécifique : hérite de **toutes** les options natives de `ChoiceType` (`choices`, `expanded`, `multiple`, `choice_label`, etc. — voir la [documentation Symfony ChoiceType](https://symfony.com/doc/current/reference/forms/types/choice.html)).

## Comportement

- `getParent()` retourne `ChoiceType::class`.
- `getBlockPrefix()` retourne `'card_radio'` — c'est ce bloc qu'il faut thémer en Twig (`{% block card_radio_widget %}`) pour obtenir le rendu "carte".

## Exemple d'usage complet

```php
use AppoloDev\SFToolboxBundle\Form\FormType\CardRadioType;

$builder->add('plan', CardRadioType::class, [
    'choices' => [
        'Basique' => 'basic',
        'Pro' => 'pro',
        'Entreprise' => 'enterprise',
    ],
    'expanded' => true,
]);
```

Thème Twig minimal côté projet (à adapter) :

```twig
{% block card_radio_widget %}
    <div class="card-radio-group">
        {{ block('choice_widget') }}
    </div>
{% endblock %}
```
