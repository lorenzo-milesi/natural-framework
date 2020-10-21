<?php

declare(strict_types = 1);

namespace NaturalFramework\Commands\Maker;

abstract class AbstractFileMaker
{
    protected string $filename;
    protected string $path;
    protected bool $exists = false;

    public function __construct($path, $filename)
    {
        $this->path = $path;
        $this->filename = $filename;
    }

    public function execute(): void
    {
        $this->checkIfExists()->createFile();
    }

    public function getFullPath(): string
    {
        return "{$this->path}/{$this->filename}.php";
    }

    public function alreadyExists(): bool
    {
        return $this->exists;
    }

    protected function fileContent(): string
    {
        return <<<HE
            <?php 
            // Hello there :D
        HE;
    }

    protected function createFile(): void
    {
        if (!$this->exists) {
            $directory = dirname($this->getFullPath());
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $open = fopen($this->getFullPath(), 'a');
            fwrite($open, $this->fileContent());
            fclose($open);
        }
    }

    protected function checkIfExists(): self
    {
        $this->exists = file_exists($this->getFullPath());

        return $this;
    }
}