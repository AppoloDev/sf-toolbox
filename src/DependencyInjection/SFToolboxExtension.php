<?php

namespace AppoloDev\SFToolboxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SFToolboxExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );
        $loader->load('services.yaml');

        $this->loadTwigTheme($container);
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
                '@SFToolbox/form_themes/tailwind_theme.html.twig',
                '@SFToolbox/form_themes/custom_radio.html.twig', // TODO: A modifier
                '@SFToolbox/form_themes/image_radio.html.twig', // TODO: A modifier
                '@SFToolbox/form_themes/tom_select.html.twig', // TODO: A modifier
            ],
        ));
    }
}
