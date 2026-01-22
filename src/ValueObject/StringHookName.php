<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\ValueObject;

use SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface;

/**
 * Value object representing a hook name as a plain string.
 *
 * The standard hook name type for WordPress compatibility, accepting any string including dynamic hook names with
 * interpolated values. Use {@link ClassNameHookName} when referencing class-based hook names for better IDE support.
 *
 * @since 1.0.0
 */
class StringHookName implements HookNameInterface
{
    /**
     * @since 1.0.0
     */
    public function __construct(private string $name)
    {
    }

    /**
     * @since 1.0.0
     */
    public function getName(): string
    {
        return $this->name;
    }
}
