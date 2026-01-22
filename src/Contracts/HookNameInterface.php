<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for hook name value objects.
 *
 * Encapsulates hook identifiers to enable type-safe hook references. Implementations may support string-based names
 * for WordPress compatibility or class-based names for improved IDE support and refactoring safety.
 *
 * @since 1.0.0
 */
interface HookNameInterface
{
    /**
     * Retrieves the string representation of this hook name.
     *
     * @return string
     *   The hook identifier used for registration and lookup.
     *
     * @since 1.0.0
     */
    public function getName(): string;
}
