<?php

namespace AppoloDev\SFToolboxBundle\Maker\Command;

use AppoloDev\SFToolboxBundle\Maker\EntityFileCreator;
use AppoloDev\SFToolboxBundle\Maker\Interact;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'make:domain:entity', description: 'Create entity in specific domain namespace')]
class MakeDomainEntityCommand extends Command
{
    use Interact;

    public function __construct(private readonly EntityFileCreator $fileCreator, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('domain', InputArgument::OPTIONAL, sprintf('Domain name of the entity to create or update (e.g. <fg=yellow>%s</>)', 'User'))
            ->addArgument('entity', InputArgument::OPTIONAL, sprintf('Name of the entity to create or update (e.g. <fg=yellow>%s</>)', 'User'))
        ;
    }

    public function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->askQuestion('domain', $input, $output);
        $this->askQuestion('entity', $input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (is_null($input->getArgument('domain')) || is_null($input->getArgument('entity'))) {
            $io->error('Invalid domain or entity name');
            return Command::FAILURE;
        }

        $command = $this->getApplication()->find('make:entity');
        $makeEntity = new ArrayInput([
            'name'    => '\\App\\Domain\\User\\Entity\\User',
        ]);

        $this->fileCreator->init($input->getArgument('domain'), $input->getArgument('entity'));

        if ($this->fileCreator->fileExist()) {
            $io->warning('Entity already exist');
            return $command->run($makeEntity, $output);
        }

        $this->fileCreator->create();
        $io->success('Entity successfully created');
        return $command->run($makeEntity, $output);
    }

}
