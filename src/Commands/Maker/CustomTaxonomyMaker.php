<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands\Maker;

class CustomTaxonomyMaker extends AbstractFileMaker
{
    private array $args;
    private string $register;
    private bool $hierarchical = true;

    public function __construct(string $path, string $filename, array $args, string $register)
    {
        parent::__construct($path, $filename);
        $this->args = $args;
        $this->register = $register;
    }

    public function setHierarchical(bool $hierarchical): self
    {
        $this->hierarchical = $hierarchical;

        return $this;
    }

    protected function fileContent(): string
    {
        return $this->hierarchical ? $this->hierarchicalTaxonomy() : $this->nonHierarchicalTaxonomy();
    }

    protected function hierarchicalTaxonomy(): string
    {
        return "<?php

declare(strict_types = 1);

namespace App\Custom\PostTypes;

use NaturalFramework\Core\StartInterface;

class {$this->filename} implements StartInterface
{
    public function start(): void
    {
        \$labels = [
            'name' => '{$this->args['labels']['name']}',
            'singular_name' => '{$this->args['labels']['singular_name']}',
            'menu_name' => '{$this->args['labels']['menu_name']}',
            'all_items' => '{$this->args['labels']['all_items']}',
            'add_new_item' => '{$this->args['labels']['add_new_item']}',
            'edit_item' => '{$this->args['labels']['edit_item']}',
            'update_item' => '{$this->args['labels']['update_item']}',
            'search_items' => '{$this->args['labels']['search_items']}',
            'new_item_name' => '{$this->args['labels']['new_item_name']}',
            'parent_item' => '{$this->args['labels']['parent_item']}',
            'parent_item_colon' => '{$this->args['labels']['parent_item_colon']}',
        ];
        
        \$args = [
            'labels' => \$labels,
            'hierarchical' => true,
            'description' => '{$this->args['description']}',
            'public' => {$this->args['public']},
            'publicly_queryable' => {$this->args['publicly_queryable']},
            'show_ui' => {$this->args['show_ui']},
            'show_in_menu' => {$this->args['show_in_menu']},
            'show_in_rest' => {$this->args['show_in_rest']},
            'show_admin_column' => {$this->args['show_admin_column']},
        ];
        
        register_taxonomy('{$this->register}', ['post'], \$args);
    }
}";
    }

    protected function nonHierarchicalTaxonomy(): string
    {
        return "<?php

declare(strict_types = 1);

namespace App\Custom\PostTypes;

use NaturalFramework\Core\StartInterface;

class {$this->filename} implements StartInterface
{
    public function start(): void
    {
        \$labels = [
            'name' => '{$this->args['labels']['name']}',
            'singular_name' => '{$this->args['labels']['singular_name']}',
            'menu_name' => '{$this->args['labels']['menu_name']}',
            'all_items' => '{$this->args['labels']['all_items']}',
            'add_new_item' => '{$this->args['labels']['add_new_item']}',
            'edit_item' => '{$this->args['labels']['edit_item']}',
            'update_item' => '{$this->args['labels']['update_item']}',
            'search_items' => '{$this->args['labels']['search_items']}',
            'new_item_name' => '{$this->args['labels']['new_item_name']}',
            'parent_item' => null,
            'parent_item_colon' => null,
            'popular_items' => '{$this->args['labels']['popular_items']}',
            'separate_items_with_commas' => '{$this->args['labels']['separate_items_with_commas']}',
            'add_or_remove_items' => '{$this->args['labels']['add_or_remove_items']}',
            'choose_from_most_used' => '{$this->args['labels']['choose_from_most_used']}',
        ];
        
        \$args = [
            'labels' => \$labels,
            'hierarchical' => false,
            'description' => '{$this->args['description']}',
            'public' => {$this->args['public']},
            'publicly_queryable' => {$this->args['publicly_queryable']},
            'show_ui' => {$this->args['show_ui']},
            'show_in_menu' => {$this->args['show_in_menu']},
            'show_in_rest' => {$this->args['show_in_rest']},
            'show_admin_column' => {$this->args['show_admin_column']},
        ];

        register_taxonomy('{$this->register}', ['post'], \$args);
    }
}";
    }
}