# SFToolboxBundle

- Classe : `AppoloDev\SFToolboxBundle\SFToolboxBundle`
- Fichier source : `src/SFToolboxBundle.php`
- Étend : `Symfony\Component\HttpKernel\Bundle\AbstractBundle`

## Rôle

Point d'entrée du bundle Symfony. Déclare l'extension de conteneur ([`SFToolboxExtension`](DependencyInjection/SFToolboxExtension.md)) qui charge les services internes et injecte de la configuration dans le projet consommateur.

## API

### `getContainerExtension(): ?ExtensionInterface`

Retourne une instance de [`SFToolboxExtension`](DependencyInjection/SFToolboxExtension.md).

## Enregistrement dans un projet consommateur

Ajouté automatiquement à `config/bundles.php` lors de l'installation via Composer (Symfony Flex) :

```php
return [
    // ...
    AppoloDev\SFToolboxBundle\SFToolboxBundle::class => ['all' => true],
];
```

Aucune configuration propre au bundle (`config/packages/sf_toolbox.yaml`) n'est nécessaire ni supportée — toute la configuration se fait via les variables d'environnement listées dans [SFToolboxExtension](DependencyInjection/SFToolboxExtension.md#api) (`SITE_TITLE`, `THEME_COLOR`, `GOOGLE_MAP_API_KEY`, `DEFAULT_URI`, plus `SENDER_EMAIL`/`SENDER_NAME` pour [Message/EmailMessage](Message/EmailMessage.md)).

## Voir aussi

- [DependencyInjection/SFToolboxExtension](DependencyInjection/SFToolboxExtension.md) — logique réelle de câblage.
