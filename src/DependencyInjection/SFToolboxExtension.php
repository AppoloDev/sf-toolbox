<?php

namespace AppoloDev\SFToolboxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

class SFToolboxExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('maker.yaml');
        $loader->load('twig.yaml');

        $this->loadTwigTheme($container);

      /*  $container->registerAttributeForAutoconfiguration(
            AsTwigComponent::class,
            static function (ChildDefinition $definition, AsTwigComponent $attribute) {
                $definition->addTag('twig.component', array_filter($attribute->serviceConfig()));
            }
        );*/

    }

    private function loadTwigTheme(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('twig.form.resources')) {
            return;
        }

        /** @var array $resources */
        $resources = $container->getParameter('twig.form.resources');

        $container->setParameter('twig.form.resources', array_merge(
            $resources,
            [
                '@SFToolbox/form/themes/tailwind_theme.html.twig',
                '@SFToolbox/form/themes/custom_radio.html.twig', // TODO: A modifier
                '@SFToolbox/form/themes/image_radio.html.twig', // TODO: A modifier
                '@SFToolbox/form/themes/tom_select.html.twig', // TODO: A modifier
            ],
        ));
    }
}
