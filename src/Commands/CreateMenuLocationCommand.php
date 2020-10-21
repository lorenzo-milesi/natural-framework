<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateMenuLocationCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'menu';

    protected function configure()
    {
        $this->setDescription('Creates a new menu location')
            ->setHelp('Creates a new menu location for WordPress Natural Theme');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new Question('Please enter a classname');
        $className = $helper->ask($input, $output, $question);

        $question = new Question('Please enter a menu name');
        $name = $helper->ask($input, $output, $question);

        $output->writeln([
            'Menu: '.$className,
            'File: ./src/Locations/Menus/'.$className.'.php',
            'Name: '.$name
        ]);

        return Command::SUCCESS;
    }
}