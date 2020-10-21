<?php

namespace NaturalFramework\Commands;

use NaturalFramework\Commands\Maker\MenuMaker;
use NaturalFramework\Commands\Maker\SidebarMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateSidebarLocationCommand extends Command
{    /** @var string */
    protected static $defaultName = 'sidebar';

    protected function configure(): void
    {
        $this->setDescription('Creates a new sidebar location')
            ->setHelp('Creates a new sidebar location for WordPress Natural Theme');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $className = $io->ask('Please enter a class name');
        $name = $io->ask('Please enter a sidebar name', $className);
        $id = $io->ask('Please enter an id', $name);

        $sidebarMaker = new SidebarMaker('src/Locations/Sidebars', $className);

        $sidebarMaker->setName($name)->setId($id)->execute();

        if ($sidebarMaker->alreadyExists()) {
            $io->warning('This file already exists');
        }

        $io->writeln([
            'Sidebar: '.$name,
            'File: ./src/Locations/Menus/'.$className.'.php',
        ]);

        $io->info([
            'To enable the new menu location, you may add :',
            "Hook::build('widgets_init', {$className}::class);",
            'inside functions.php file',
        ]);

        return Command::SUCCESS;
    }
}