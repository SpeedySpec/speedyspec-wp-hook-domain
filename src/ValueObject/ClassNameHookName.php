<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\ValueObject;

class ClassNameHookName implements \SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface
{
    /**
     * HookNameString maintains backwards compatibility with the WordPress API.
     *
     * It is used for hooks that are strings, like most actions and filters.
     *
     * Can also be used for hooks that use `::class` for hooks.
     *
     * @param class-name $name
     */
    public function __construct(private string $name)
    {
    }

    /**
     * @return class-name
     */
    public function getName(): string
    {
        return $this->name;
    }
}
