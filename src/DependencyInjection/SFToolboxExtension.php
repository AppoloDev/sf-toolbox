<?php

namespace AppoloDev\SFToolboxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SFToolboxExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        /** @var array $bundles */
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['TwigBundle'])) {
            $container->prependExtensionConfig('twig', [
                'globals' => [
                    'siteTitle' => '%env(SITE_TITLE)%',
                    'themeColor' => '%env(THEME_COLOR)%',
                    'googleMapApiKey' => '%env(GOOGLE_MAP_API_KEY)%',
                ],
                'form_themes' => [
                    '@SFToolbox/form/themes/tailwind_theme.html.twig',
                    '@SFToolbox/form/themes/vich.html.twig',
                    '@SFToolbox/form/widgets/card_radio.html.twig',
                    '@SFToolbox/form/widgets/tom_select.html.twig',
                    '@SFToolbox/form/widgets/geo_localizable.html.twig',
                ],
            ]);
        }

        if (isset($bundles['KnpPaginatorBundle'])) {
            $container->prependExtensionConfig('knp_paginator', [
                'template' => [
                    'pagination' => '@SFToolbox/paginator/pagination.html.twig',
                    'sortable' => '@SFToolbox/paginator/sortable.html.twig',
                ],
            ]);
        }

        if (isset($bundles['TwigComponentBundle'])) {
            $container->prependExtensionConfig('twig_component', [
                'defaults' => [
                    'AppoloDev\SFToolboxBundle\UI\Atoms\Alert\\' => '@SFToolboxBundle/ui/atoms/alert/',
                    'AppoloDev\SFToolboxBundle\UI\Atoms\Badge\\' => '@SFToolboxBundle/ui/atoms/badge/',
                    'AppoloDev\SFToolboxBundle\UI\Atoms\Button\\' => '@SFToolboxBundle/ui/atoms/button/',
                    'AppoloDev\SFToolboxBundle\UI\Atoms\Toast\\' => '@SFToolboxBundle/ui/atoms/toast/',
                    'AppoloDev\SFToolboxBundle\UI\Molecules\Card\\' => '@SFToolboxBundle/ui/molecules/card/',
                    'AppoloDev\SFToolboxBundle\UI\Molecules\Form\\' => '@SFToolboxBundle/ui/molecules/form/',
                    'AppoloDev\SFToolboxBundle\UI\Molecules\Generic\\' => '@SFToolboxBundle/ui/molecules/generic/',
                    'AppoloDev\SFToolboxBundle\UI\Molecules\List\\' => '@SFToolboxBundle/ui/molecules/list/',
                    'AppoloDev\SFToolboxBundle\UI\Organisms\Dropdown\\' => '@SFToolboxBundle/ui/organisms/dropdown/',
                    'AppoloDev\SFToolboxBundle\UI\Organisms\User\\' => '@SFToolboxBundle/ui/organisms/user/',
                    'AppoloDev\SFToolboxBundle\UI\Templates\Card\\' => '@SFToolboxBundle/ui/templates/card/',
                    'AppoloDev\SFToolboxBundle\UI\Templates\Form\\' => '@SFToolboxBundle/ui/templates/form/',
                    'AppoloDev\SFToolboxBundle\UI\Templates\List\\' => '@SFToolboxBundle/ui/templates/list/',
                ],
            ]);
        }

        $container->prependExtensionConfig('framework', [
            'router' => [
                'default_uri' => '%env(DEFAULT_URI)%',
            ],
        ]);
    }
}
