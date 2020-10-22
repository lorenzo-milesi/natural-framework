<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands\Maker;

class CustomPostTypeMaker extends AbstractFileMaker
{
    private array $args;
    private string $register;

    public function __construct(string $path, string $filename, array $args, string $register)
    {
        parent::__construct($path, $filename);
        $this->args = $args;
        $this->register = $register;
    }

    protected function fileContent(): string
    {
        return <<<HE
<?php

declare(strict_types = 1);

namespace App\Custom\PostTypes;

use NaturalFramework\Core\StartInterface;

class {$this->filename} implements StartInterface
{
    public function start(): void
    {
        \$labels = [
            'name' => {$this->args['labels']['name']},
            'singular_name' => {$this->args['labels']['singular_name']},
            'menu_name' => {$this->args['labels']['menu_name']},
            'all_items' => {$this->args['labels']['all_items']},
            'view_item' => {$this->args['labels']['view_item']},
            'add_new_item' => {$this->args['labels']['add_new_item']},
            'add_new' => {$this->args['labels']['add_new']},
            'edit_item' => {$this->args['labels']['edit_item']},
            'update_item' => {$this->args['labels']['update_item']},
            'search_items' => {$this->args['labels']['search_items']},
            'not_found' => {$this->args['labels']['not_found']},
            'not_found_in_trash' => {$this->args['labels']['not_found_in_trash']},
        ];
        
        \$args = [
            'label' => {$this->args['label']},
            'description' => {$this->args['description']},
            'menu_icon' => {$this->args['menu_icon']},
            'labels' => \$labels,
            'supports' => {$this->args['supports']},
            'hierarchical' => {$this->args['hierarchical']},
            'public' => {$this->args['public']},
            'show_ui' => {$this->args['show_ui']},
            'show_in_menu' => {$this->args['show_in_menu']},
            'show_in_nav_menus' => {$this->args['show_in_nav_menus']},
            'show_in_admin_bar' => {$this->args['show_in_admin_bar']},
            'menu_position' => {$this->args['menu_position']},
            'can_export' => {$this->args['can_export']},
            'has_archive' => {$this->args['has_archive']},
            'exclude_from_search' => {$this->args['exclude_from_search']},
            'publicly_queryable' => {$this->args['publicly_queryable']},
            'capability_type' => {$this->args['capability_type']},
            'show_in_rest' => {$this->args['show_in_rest']},
        ];
        
        register_post_type('{$this->register}', \$args);
    }
}
HE;
    }

}