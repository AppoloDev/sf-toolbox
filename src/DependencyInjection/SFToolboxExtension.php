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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['TwigBundle'])) {
            $container->prependExtensionConfig('twig', [
                'globals' => [
                    'siteTitle' => '%env(SITE_TITLE)%',
                    'themeColor' => '%env(THEME_COLOR)%'
                ],
                'form_themes' => [
                    '@SFToolbox/form/themes/tailwind_theme.html.twig',
                    '@SFToolbox/form/widgets/card_radio.html.twig',
                    '@SFToolbox/form/widgets/tom_select.html.twig',
                    '@SFToolbox/form/widgets/geo_localizable.html.twig',
                ]
            ]);
        }

        if (isset($bundles['KnpPaginatorBundle'])) {
            $container->prependExtensionConfig('knp_paginator', [
                'template' => [
                    'pagination' => '@SFToolbox/paginator/pagination.html.twig',
                    'sortable' => '@SFToolbox/paginator/sortable.html.twig'
                ]
            ]);
        }

        $container->prependExtensionConfig('framework', [
            'router' => [
                'default_uri' => '%env(DEFAULT_URI)%',
            ]
        ]);
    }
}
