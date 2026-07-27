# SFToolboxExtension

- Classe : `AppoloDev\SFToolboxBundle\DependencyInjection\SFToolboxExtension`
- Fichier source : `src/DependencyInjection/SFToolboxExtension.php`
- Implémente : `Symfony\Component\DependencyInjection\Extension\Extension`, `PrependExtensionInterface`

## Rôle

Extension de conteneur de services du bundle. Charge la configuration interne du bundle (`config/services.yaml`) et **injecte de la configuration dans le projet consommateur** au démarrage, via `PrependExtensionInterface`. Vous n'interagissez jamais directement avec cette classe — elle est instanciée automatiquement par [`SFToolboxBundle`](../SFToolboxBundle.md).

## API

### `load(array $configs, ContainerBuilder $container): void`

Charge `config/services.yaml` (déclaration des services du bundle — voir plus bas).

### `prepend(ContainerBuilder $container): void`

Exécuté **avant** la compilation finale du conteneur, pour toute extension enregistrée. Injecte automatiquement, dans la configuration du **projet consommateur** :

- Si `TwigBundle` est enregistré : des globales Twig `siteTitle`, `themeColor`, `googleMapApiKey`, chacune liée à une variable d'environnement (`SITE_TITLE`, `THEME_COLOR`, `GOOGLE_MAP_API_KEY`).
- Toujours : `framework.router.default_uri`, lié à la variable d'environnement `DEFAULT_URI`.

> **Conséquence pour le projet consommateur** : ces variables d'environnement (`SITE_TITLE`, `THEME_COLOR`, `GOOGLE_MAP_API_KEY`, `DEFAULT_URI`) doivent être définies (`.env`/`.env.local`/variables système), sinon Symfony lèvera une erreur au premier accès à ces paramètres (variable d'environnement non résolue).

## Configuration chargée (`config/services.yaml`)

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    AppoloDev\SFToolboxBundle\:
        resource: '../src/'
        exclude:
            - '../src/DependencyInjection/'
            - '../src/Domain/Entity/'
            - '../src/Domain/Repository/'

    AppoloDev\SFToolboxBundle\Security\Http\EventListener\IsNotGrantedAttributeListener:
        arguments:
            - '@security.authorization_checker'
            - '@security.is_granted_attribute_expression_language'
        tags: ['kernel.event_subscriber']
```

Toutes les classes sous `src/` sont **autowired et autoconfigured** automatiquement — c'est pourquoi la plupart des services du bundle (subscribers, handlers, commandes...) ne nécessitent aucune déclaration manuelle côté projet consommateur.

Sont **exclues** de l'auto-enregistrement (donc jamais instanciées comme services) :
- `DependencyInjection/` — classes d'infrastructure du bundle lui-même.
- `Domain/Entity/` — traits/concerns, pas des services.
- `Domain/Repository/` — traits Criteria, pas des services (les repositories concrets du projet consommateur, eux, restent des services Doctrine normaux).

## Voir aussi

- [SFToolboxBundle](../SFToolboxBundle.md) — bundle principal, instancie cette extension.
