<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\ValueObject;

use SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface;

/**
 * Value object representing a hook name as a fully-qualified class name.
 *
 * Enables IDE support for hook names that correspond to classes, providing autocompletion, refactoring safety, and
 * usage tracking. Use {@link StringHookName} for traditional string-based WordPress hook names.
 *
 * @since 1.0.0
 */
class ClassNameHookName implements HookNameInterface
{
    /**
     * @param class-string $name
     *   A fully-qualified class name.
     *
     * @since 1.0.0
     */
    public function __construct(private string $name)
    {
    }

    /**
     * @return class-string
     *   The fully-qualified class name.
     *
     * @since 1.0.0
     */
    public function getName(): string
    {
        return $this->name;
    }
}
