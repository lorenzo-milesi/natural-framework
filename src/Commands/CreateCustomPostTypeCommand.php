<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands;

use IlluminateAgnostic\Str\Support\Str;
use NaturalFramework\Commands\Maker\CustomPostTypeMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCustomPostTypeCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'postType';

    private SymfonyStyle $io;
    private string $plural;
    private string $singular;

    protected function configure()
    {
        $this->setDescription('Creates a new post type')
            ->setHelp('Creates a new WordPress custom post type');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $className = $this->io->ask('Please enter a class name');

        $this->plural = Str::plural($className);
        $this->singular = Str::singular($className);

        $args = $this->generatePostType();

        $register = $this->io->ask('Name to register', Str::slug($this->plural));

        $maker = new CustomPostTypeMaker('src/Custom/PostTypes', $className, $args, $register);

        $maker->execute();

        if($maker->alreadyExists()) {
            $this->io->warning('This file already exists');
        }

        $this->io->writeln([
            'Post Type: '.$register,
            'File: ./src/Custom/PostTypes/'.$className.'.php',
        ]);

        $this->io->info([
            'To enable the new custom post type, you may add :',
            "Hook::build('init', {$className}::class);",
            'inside functions.php file',
        ]);

        return Command::SUCCESS;
    }

    private function generatePostType(): array
    {
        $this->io->title('1. Let\'s set the Post Type labels :');

        $labels = $this->generateLabels();

        $this->io->title('2. Let\'s add some configuration :');

        $supportSelect = $this->supportSelect();

        return [
            'label' => $this->io->ask('Label', Str::lower($this->plural)),
            'description' => $this->io->ask('Description'),
            'menu_icon' => $this->io->ask('Menu icon', 'dashicons-controls-play'),
            'labels' => $labels,
            'supports' => $this->io->askQuestion($supportSelect),
            'hierarchical' => $this->io->confirm('Is it hierarchical ?', false),
            'public' => $this->io->confirm('Is it public ?', true),
            'show_ui' => $this->io->confirm('Show ui ?', true),
            'show_in_menu' => $this->io->confirm('Show in menu ?', true),
            'show_in_nav_menus' => $this->io->confirm('Show in nav menu ?', true),
            'show_in_admin_bar' => $this->io->confirm('Show in admin bar ?', true),
            'menu_position' => $this->io->ask('Menu Position', '5'),
            'can_export' => $this->io->confirm('Can export ?', true),
            'has_archive' => $this->io->confirm('Has archive ?', true),
            'exclude_from_search' => $this->io->confirm('Exclude from search ?', false),
            'publicly_queryable' => $this->io->confirm('Publicly queryable ?', true),
            'capability_type' => 'post',
            'show_in_rest' => $this->io->confirm('Show in rest ?', true),
        ];
    }

    private function generateLabels(): array
    {
        return [
            'name' => $this->io->ask('Please enter a plural name', $this->plural),
            'singular_name' => $this->io->ask('Please enter a singular name', $this->singular),
            'menu_name' => $this->io->ask('Please enter a menu name', $this->plural),
            'all_items' => $this->io->ask('All items', "All $this->plural"),
            'view_item' => $this->io->ask('View item', "View $this->singular"),
            'add_new_item' => $this->io->ask('Add new item', "Add New $this->singular"),
            'add_new' => $this->io->ask('Add new', 'Add'),
            'edit_item' => $this->io->ask('Edit item', "Edit $this->singular"),
            'update_item' => $this->io->ask('Update item', "Update $this->singular"),
            'search_items' => $this->io->ask('Search items', "Search $this->singular"),
            'not_found' => $this->io->ask('Not found', "No $this->singular found"),
            'not_found_in_trash' => $this->io->ask('Not found in trash', "No $this->singular found in trash"),
        ];
    }

    private function supportSelect(): ChoiceQuestion
    {
        $supportSelect = new ChoiceQuestion('Select supports',
            ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions'],
            '0,1'
        );
        $supportSelect->setMultiselect(true);

        return $supportSelect;
    }
}