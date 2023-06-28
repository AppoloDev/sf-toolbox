<?php

namespace AppoloDev\SFToolboxBundle\Maker\Command;

use AppoloDev\SFToolboxBundle\Maker\FileCreator\EntityFileCreator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'make:domain:entity', description: 'Create entity in specific domain namespace')]
class MakeDomainEntityCommand extends Command
{
    use InteractCommand;

    public function __construct(private readonly EntityFileCreator $fileCreator, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('domain', InputArgument::OPTIONAL, sprintf('Domain name of the entity to create or update (e.g. <fg=yellow>%s</>)', 'User'))
            ->addArgument('entity', InputArgument::OPTIONAL, sprintf('Name of the entity to create or update (e.g. <fg=yellow>%s</>)', 'User'))
            ->addArgument('mapping', InputArgument::OPTIONAL, 'Did you configure the mapping');
    }

    public function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->askQuestion('domain', $input, $output);
        $this->askQuestion('entity', $input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $domain = $input->getArgument('domain');
        $entity = $input->getArgument('entity');

        $io = new SymfonyStyle($input, $output);

        if (is_null($domain) || is_null($entity)) {
            $io->error('Invalid domain or entity name');
            return Command::FAILURE;
        }

        $this->fileCreator->init(['domain' => $domain, 'entity' => $entity]);

        if ($this->fileCreator->filesExist()) {
            $io->warning('Entity already exist');
        } else {
            $this->fileCreator->create();
            $io->success('Entity successfully created');
        }

        while ($input->getArgument('mapping') !== true) {
            $this->askConfirmation('mapping', $input, $output);
            if ($input->getArgument('mapping') === false) {
                $this->displayMapping($io, $domain);
            }
        }

        $process = $this->runMake($domain, $entity);
        if ($process === false) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;

    }

    protected function runMake(string $domain, string $entity): bool
    {
        $namespace = sprintf('\App\Domain\%s\Entity\%s', $domain, $entity);
        $process = new Process(['php', 'bin/console', 'make:entity', $namespace]);
        $process->setTty(true);
        $process->start();
        $process->wait();
        return $process->isSuccessful();
    }

    protected function displayMapping(SymfonyStyle $io, string $domain): void
    {
        $io->note('In config/packages/doctrine.yaml, you should this add under mappings key :');
        $html = <<<HTML
                $domain:
                    is_bundle: false
                    type: attribute
                    dir: '%kernel.project_dir%/src/Domain/$domain/Entity'
                    prefix: 'App\Domain\\$domain\Entity'
                    alias: $domain
        HTML;
        $io->text($html);
    }
}
