<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands;

use NaturalFramework\Commands\Maker\Location\Location\MenuMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateMenuLocationCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'menu';

    protected function configure(): void
    {
        $this->setDescription('Creates a new menu location')
            ->setHelp('Creates a new menu location for WordPress Natural Theme');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $className = $io->ask('Please enter a class name');
        $menuName = $io->ask('Please enter a menu name', $className);

        $menuMaker = new MenuMaker('src/Locations/Menus', $className);

        $menuMaker->setMenuName($menuName)->execute();

        if ($menuMaker->alreadyExists()) {
            $io->warning('This file already exists');
        }

        $io->writeln([
            'Menu: '.$menuName,
            'File: ./src/Locations/Menus/'.$className.'.php',
        ]);

        $io->info([
            'To enable the new menu location, you may add :',
            "Hook::build('after_setup_theme', {$className}::class);",
            'inside functions.php file',
        ]);

        return Command::SUCCESS;
    }
}