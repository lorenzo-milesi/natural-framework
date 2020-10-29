<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands;

use IlluminateAgnostic\Str\Support\Str;
use NaturalFramework\Commands\Maker\CustomTaxonomyMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCustomTaxonomyCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'taxonomy';

    private SymfonyStyle $io;
    private string $plural;
    private string $singular;
    private bool $hierarchical = true;

    protected function configure()
    {
        $this->setDescription('Creates a new taxonomy')
            ->setHelp('Creates a new WordPress custom taxonomy');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $className = $this->io->ask('Please enter a class name');

        $this->plural = Str::plural($className);
        $this->singular = Str::singular($className);

        $this->io->writeln('Let\' define if the taxonomy will be hierarchical (like categories) or not (like tags)');

        $this->hierarchical = $this->io->confirm('Is it hierarchical ?', true);

        $args = $this->generateTaxonomy();

        $register = $this->io->ask('Name to register', Str::slug($this->plural));

        $maker = new CustomTaxonomyMaker('src/Custom/Taxonomies', $className, $args, $register);
        $maker->setHierarchical($this->hierarchical);
        $maker->execute();

        if ($maker->alreadyExists()) {
            $this->io->warning('This file already exists');
        }

        $this->io->writeln([
            'Taxonomy'.$register,
            'File: ./src/Custom/Taxonomies/'.$className.'.php',
        ]);

        $this->io->info([
            'To enable the new custom taxonomy, you may add :',
            "Hook::build('init', {$className}::class);",
            'inside functions.php file.',
            '',
            'And if you add custom post types',
            'don\'t forget to add them inside your newly created class :D'
        ]);

        return Command::SUCCESS;
    }

    private function generateTaxonomy(): array
    {
        return $this->hierarchical ? $this->generateHierarchicalTaxonomy() : $this->generateNonHierarchicalTaxonomy();
    }

    private function generateHierarchicalTaxonomy(): array
    {
        $this->io->title('1. Let\'s set the labels :');

        $labels = $this->generateLabels();

        $labels['parent_item'] = $this->io->ask('Parent item', "Parent $this->singular");
        $labels['parent_item_colon'] = $this->io->ask('Parent item:', "Parent $this->singular:");

        $this->io->title('2. Let\'s add some configuration :');

        return [
            'labels' => $labels,
            'description' => $this->io->ask('Description'),
            'public' => $this->io->confirm('Is it public ?', true) ? 'true' : 'false',
            'publicly_queryable' => $this->io->confirm('Publicly queryable ?', true) ? 'true' : 'false',
            'show_ui' => $this->io->confirm('Show ui ?', true) ? 'true' : 'false',
            'show_in_menu' => $this->io->confirm('Show in menu ?', true) ? 'true' : 'false',
            'show_in_nav_menus' => $this->io->confirm('Show in nav menu ?', true) ? 'true' : 'false',
            'show_in_rest' => $this->io->confirm('Show in rest ?', true) ? 'true' : 'false',
            'show_admin_column' => $this->io->confirm('Show admin column ?', true) ? 'true' : 'false',
        ];
    }

    private function generateNonHierarchicalTaxonomy(): array
    {
        $this->io->title('1. Let\'s set the labels :');

        $labels = $this->generateLabels();

        $labels['popular_items'] = $this->io->ask('Popular Items', "Popular $this->plural");
        $labels['separate_items_with_commas'] = $this->io->ask('Separate items with commas', "Separate $this->plural with commas");
        $labels['add_or_remove_items'] = $this->io->ask('Add or remove items', "Add or remove $this->plural");
        $labels['choose_from_most_used'] = $this->io->ask('Choose from most used', 'Choose from most used');


        $this->io->title('2. Let\'s add some configuration :');

        return [
            'labels' => $labels,
            'description' => $this->io->ask('Description'),
            'public' => $this->io->confirm('Is it public ?', true) ? 'true' : 'false',
            'publicly_queryable' => $this->io->confirm('Publicly queryable ?', true) ? 'true' : 'false',
            'show_ui' => $this->io->confirm('Show ui ?', true) ? 'true' : 'false',
            'show_in_menu' => $this->io->confirm('Show in menu ?', true) ? 'true' : 'false',
            'show_in_nav_menus' => $this->io->confirm('Show in nav menu ?', true) ? 'true' : 'false',
            'show_in_rest' => $this->io->confirm('Show in rest ?', true) ? 'true' : 'false',
            'show_admin_column' => $this->io->confirm('Show admin column ?', true) ? 'true' : 'false',
        ];
    }

    private function generateLabels(): array
    {
        return [
            'name' => $this->io->ask('Please enter a plural name', $this->plural),
            'singular_name' => $this->io->ask('Please enter a singular name', $this->singular),
            'menu_name' => $this->io->ask('Please enter a menu name', $this->plural),
            'all_items' => $this->io->ask('All items', "All $this->plural"),
            'add_new_item' => $this->io->ask('Add new item', "Add New $this->singular"),
            'edit_item' => $this->io->ask('Edit item', "Edit $this->singular"),
            'update_item' => $this->io->ask('Update item', "Update $this->singular"),
            'search_items' => $this->io->ask('Search items', "Search $this->singular"),
            'new_item_name' => $this->io->ask('New item name', "New $this->singular Name"),
        ];
    }
}