<?php

namespace AppoloDev\SFToolboxBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

final class ScriptHandler
{
    private const SKILL_RELATIVE_PATH = '.claude/skills/sf-toolbox';

    /**
     * Called from a consuming project's composer.json `post-install-cmd`/`post-update-cmd`
     * to (re)link this bundle's Claude Code skill into the project's own `.claude/skills/`,
     * so it always matches the exact bundle version installed in that project.
     */
    public static function installClaudeSkill(Event $event): void
    {
        $io = $event->getIO();
        $filesystem = new Filesystem();

        $vendorDir = (string) $event->getComposer()->getConfig()->get('vendor-dir');
        $projectDir = \dirname($vendorDir);

        $source = $vendorDir.'/appolodev/sf-toolbox/'.self::SKILL_RELATIVE_PATH;
        $target = $projectDir.'/'.self::SKILL_RELATIVE_PATH;

        if (!is_dir($source)) {
            return;
        }

        if (is_link($target)) {
            $resolvedCurrentTarget = realpath(\dirname($target).'/'.(string) readlink($target));
            if (false !== $resolvedCurrentTarget && $resolvedCurrentTarget === realpath($source)) {
                return;
            }
        }

        if ($filesystem->exists($target) && !is_link($target)) {
            $io->writeError(sprintf(
                '<comment>[sf-toolbox]</comment> "%s" already exists and is not managed by sf-toolbox, skipping (remove it to let sf-toolbox link it).',
                self::SKILL_RELATIVE_PATH
            ));

            return;
        }

        $filesystem->mkdir(\dirname($target));

        if ($filesystem->exists($target)) {
            $filesystem->remove($target);
        }

        $relativeSource = $filesystem->makePathRelative($source, \dirname($target).'/');

        try {
            $filesystem->symlink(rtrim($relativeSource, '/'), $target);
            $io->write(sprintf('<info>[sf-toolbox]</info> Claude Code skill linked to %s', self::SKILL_RELATIVE_PATH));
        } catch (IOException) {
            $filesystem->mirror($source, $target);
            $io->write(sprintf('<info>[sf-toolbox]</info> Claude Code skill copied to %s (symlinks unsupported here)', self::SKILL_RELATIVE_PATH));
        }
    }
}
