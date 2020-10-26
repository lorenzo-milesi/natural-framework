<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands;

use NaturalFramework\Commands\Maker\Display\WalkerMenuMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateWalkerMenuCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'walker';

    protected function configure()
    {
        $this->setDescription('Creates a new WordPress Walker Menu')
            ->setHelp('Creates a new WordPress Walker Menu');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $className = $io->ask('Please enter a class name');

        $walkerMenuMaker = new WalkerMenuMaker('src/Display/Walkers', $className);
        $walkerMenuMaker->execute();

        if($walkerMenuMaker->alreadyExists()) {
            $io->warning('This file already exists');
        }

        $io->writeln([
            'Walker: '.$className,
            'File: ./src/Display/Walkers/'.$className.'.php',
        ]);

        return Command::SUCCESS;
    }
}