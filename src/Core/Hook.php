<?php

declare(strict_types = 1);

namespace NaturalFramework\Core;

/**
 * Class Hook
 * @package NaturalFramework\Core
 */
final class Hook
{
    /**
     * @var string
     * WordPress hook to be called for each starters.
     */
    private string $hook;

    /**
     * @var StartInterface[]
     * Starters to load on hook.
     */
    private array $starters;

    /**
     * Hook constructor.
     *
     * @param  string  $hook
     * @param  StartInterface  ...$starters
     */
    public function __construct(string $hook, StartInterface ...$starters)
    {
        $this->hook = $hook;
        $this->starters = $starters;
        /** @phpstan-ignore-next-line  */
        add_action($this->hook, [$this, 'start']);
    }

    /**
     * Starts every starter.
     */
    public function start(): void
    {
        foreach ($this->starters as $starter) {
            (new $starter())->start();
        }
    }

    /**
     * @param  mixed  ...$params
     *
     * @return self
     */
    public static function build(...$params): self
    {
        return new self(...$params);
    }
}