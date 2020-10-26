<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands\Maker\Location\Location;

use NaturalFramework\Commands\Maker\Location\AbstractFileMaker;

class SidebarMaker extends AbstractFileMaker
{
    protected string $name;
    protected string $id;

    public function setName(string $name): SidebarMaker
    {
        $this->name = $name;

        return $this;
    }

    public function setId(string $id): SidebarMaker
    {
        $this->id = $id;

        return $this;
    }

    protected function fileContent(): string
    {
        return <<<HE
<?php

declare(strict_types = 1);

namespace App\Locations\Sidebars;

use NaturalFramework\Core\StartInterface;

class {$this->filename} implements StartInterface
{
    public function start(): void
    {
        register_sidebar([
            'name' => '$this->name',
            'id' => '$this->id',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<h2>',
            'after_title'   => '</h2>'
        ]);
    }
}
HE;
    }
}