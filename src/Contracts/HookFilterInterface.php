<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Marker interface for filter-style hook callbacks.
 *
 * Filters transform a value and return the modified result. This interface distinguishes filter callbacks from action
 * callbacks in the type system, enabling the hook system to chain return values through multiple callbacks.
 *
 * @since 1.0.0
 */
interface HookFilterInterface
{
    /**
     * Transforms the value and returns the result.
     *
     * @return mixed
     *   The filtered value, which may be the original or a modified version.
     *
     * @since 1.0.0
     */
    #[ReturnTypeWillChange]
    public function filter(mixed $value, ...$args): mixed;
}
