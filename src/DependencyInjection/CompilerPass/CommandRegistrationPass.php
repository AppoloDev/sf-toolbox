<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppoloDev\SFToolboxBundle\DependencyInjection\CompilerPass;

use AppoloDev\SFToolboxBundle\Console\Maker\MakeDomainEntityCommand;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandRegistrationPass implements CompilerPassInterface
{
    public const MAKER_TAG = 'maker.command';

    public function process(ContainerBuilder $container): void
    {
        $commandDefinition = new ChildDefinition('maker.auto_command.abstract');
        $commandDefinition->setClass(MakeDomainEntityCommand::class);
        $commandDefinition->addTag('console.command', ['command' => 'make:domain:entity', 'description' => 'Create entity in specific Domain namespace']);
        $container->setDefinition(sprintf('maker.auto_command.%s', Str::asTwigVariable('make:domain:entity')), $commandDefinition);
    }
}
