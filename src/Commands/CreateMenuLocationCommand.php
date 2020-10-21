<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMenuLocationCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'menu';

    protected function configure()
    {
        $this->setDescription('Creates a new menu location')
            ->setHelp('Creates a new menu location for WordPress Natural Theme')
            ->addArgument('name', InputArgument::REQUIRED, 'Menu\'s class name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating a new Menu...');
        $output->writeln('Menu: '.$input->getArgument('name'));

        return Command::SUCCESS;
    }
}