<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\ValueObject;
class StringHookName implements \SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface
{
    /**
     * HookNameString maintains backwards compatibility with the WordPress API.
     *
     * It is used for hooks that are strings, like most actions and filters.
     *
     * Can also be used for hooks that use `::class` for hooks.
     */
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
