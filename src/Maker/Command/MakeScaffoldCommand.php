<?php

namespace AppoloDev\SFToolboxBundle\Maker\Command;

use AppoloDev\SFToolboxBundle\Maker\FileCreator\ScaffoldFileCreator;
use AppoloDev\SFToolboxBundle\Maker\Interact;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'make:scaffold', description: 'Generate CRUD in specific area')]
class MakeScaffoldCommand extends Command
{
    use Interact;

    public function __construct(private readonly ScaffoldFileCreator $fileCreator, string $name = null) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('domain', InputArgument::OPTIONAL, sprintf('Domain name of the entity (e.g. <fg=yellow>%s</>)', 'User'))
            ->addArgument('entity', InputArgument::OPTIONAL, sprintf('Name of the entity (e.g. <fg=yellow>%s</>)', 'User'))
            ->addArgument('area', InputArgument::OPTIONAL, sprintf('Area where be place the file (e.g. <fg=yellow>%s</>)', 'Admin'))
            ->addArgument('prefix', InputArgument::OPTIONAL, sprintf('Prefix for template name, route name... (e.g. <fg=yellow>%s</>)', 'user'))
            ->addArgument('routePath', InputArgument::OPTIONAL, sprintf('Singular path for route  (e.g. <fg=yellow>%s</>)', 'utilisateur'))
        ;
    }

    public function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->askQuestion('domain', $input, $output);
        $this->askQuestion('entity', $input, $output);
        $this->askQuestion('area', $input, $output);
        $this->askQuestion('prefix', $input, $output);
        $this->askQuestion('routePath', $input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $domain = $input->getArgument('domain');
        $entity = $input->getArgument('entity');
        $area = $input->getArgument('area');
        $prefix = $input->getArgument('prefix');
        $routePath = $input->getArgument('routePath');

        $io = new SymfonyStyle($input, $output);

        $entityClass = '\App\Domain\\'.$domain.'\Entity\\'.$entity;

        if (!class_exists($entityClass)) {
            $io->error('Invalid entity class');
            return Command::FAILURE;
        }

        $this->fileCreator->init([
            'domain' => $domain,
            'entity' => $entity,
            'area' => $area,
            'prefix' => $prefix,
            'routePath' => $routePath,
        ]);

        if ($this->fileCreator->filesExist()) {
            $io->warning('Files already exist');
        } else {
            $this->fileCreator->create();
            $io->success('Files successfully created');
        }

        return Command::SUCCESS;
    }
}
