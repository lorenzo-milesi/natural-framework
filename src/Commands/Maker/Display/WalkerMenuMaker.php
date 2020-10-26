<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands\Maker\Display;

use NaturalFramework\Commands\Maker\AbstractFileMaker;

class WalkerMenuMaker extends AbstractFileMaker
{
    protected function fileContent(): string
    {
        return <<<HE
<?php

declare(strict_types = 1);

namespace App\Display\Walkers;

use Walker_Nav_Menu;

class {$this->filename} extends Walker_Nav_Menu
{
    /**
     * Let's do something great ! See docs for usage !
     * https://developer.wordpress.org/reference/classes/walker_nav_menu/
     */
}
HE;
    }
}