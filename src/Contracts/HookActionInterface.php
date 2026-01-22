<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Marker interface for action-style hook callbacks.
 *
 * Actions perform side effects without returning a value. This interface distinguishes action callbacks from filter
 * callbacks in the type system, enabling infrastructure to optimize dispatch behavior.
 *
 * @since 1.0.0
 */
interface HookActionInterface
{
    /**
     * Executes the action with the provided arguments.
     *
     * @since 1.0.0
     */
    public function dispatch(...$args): void;
}
