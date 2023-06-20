<?php

namespace AppoloDev\SFToolboxBundle\Maker;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

trait Interact
{
    protected function askQuestion(string $argName, InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $domainArgument = $this->getDefinition()->getArgument($argName);
        $question = new Question($domainArgument->getDescription());
        $domainValue = $io->askQuestion($question);
        $input->setArgument($argName, $domainValue);
    }
}