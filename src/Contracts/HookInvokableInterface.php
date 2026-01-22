<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for a callable wrapper that can be invoked as a hook callback.
 *
 * Wraps PHP callables (closures, functions, methods) to provide a consistent interface and unique identification for
 * callback management. Implementations must generate a stable unique name for callback lookup and removal.
 *
 * @since 1.0.0
 */
interface HookInvokableInterface
{
    /**
     * Retrieves the unique identifier for this callback.
     *
     * @return string
     *   A stable identifier used to match callbacks during removal operations.
     *
     * @since 1.0.0
     */
    public function getName(): string;

    /**
     * Invokes the underlying callable with the provided arguments.
     *
     * @return mixed
     *   The return value from the underlying callable.
     *
     * @since 1.0.0
     */
    public function __invoke(...$args): mixed;
}
