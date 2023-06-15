<?php

namespace AppoloDev\SFToolboxBundle;

use AppoloDev\SFToolboxBundle\DependencyInjection\SFToolboxExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SFToolboxBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SFToolboxExtension();
    }
}