<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands\Maker;

class MenuMaker extends AbstractFileMaker
{
    protected string $menuName;

    public function setMenuName(string $menuName): MenuMaker
    {
        $this->menuName = $menuName;

        return $this;
    }

    protected function fileContent(): string
    {
        return <<<HE
<?php

declare(strict_types = 1);

namespace App\Locations\Menus;

use NaturalFramework\Core\StartInterface;

class {$this->filename} implements StartInterface
{
    public function start(): void
    {
        register_nav_menu('{$this->filename}', '$this->menuName');
    }
}
HE;
    }
}