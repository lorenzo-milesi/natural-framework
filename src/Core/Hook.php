<?php

declare(strict_types = 1);

namespace NaturalFramework\Core;

use NaturalFramework\Exceptions\MustImplementException;

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
     * @param  string  ...$starters
     *
     * @throws MustImplementException
     */
    public function __construct(string $hook, string ...$starters)
    {
        $this->hook = $hook;
        $this->starters = [];
        foreach ($starters as $starter) {
            if (!in_array(StartInterface::class, class_implements($starter))) {
                throw new MustImplementException("$starter must implement ".StartInterface::class);
            }
            $this->starters[] = new $starter();
        }
        /** @phpstan-ignore-next-line */
        add_action($this->hook, [$this, 'start']);
    }

    /**
     * Starts every starter.
     */
    public function start(): void
    {
        foreach ($this->starters as $starter) {
            $starter->start();
        }
    }

    /**
     * @param  mixed  ...$params
     *
     * @return self
     * @throws MustImplementException
     */
    public static function build(...$params): self
    {
        return new self(...$params);
    }
}