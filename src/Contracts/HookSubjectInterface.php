<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for managing callbacks registered to a single hook.
 *
 * Implements the Observer pattern where callbacks subscribe to a hook and are notified when the hook is dispatched.
 * Infrastructure implementations must maintain callback ordering by priority and support both action (dispatch) and
 * filter (transform) execution modes.
 *
 * @since 1.0.0
 */
interface HookSubjectInterface
{
    /**
     * Registers a callback to this hook.
     *
     * @since 1.0.0
     */
    public function add(HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;

    /**
     * Unregisters a callback from this hook.
     *
     * @since 1.0.0
     */
    public function remove(HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;

    /**
     * Removes all callbacks, optionally filtered by priority.
     *
     * @param int|null $priority
     *   When provided, only removes callbacks at this priority level.
     *
     * @since 1.0.0
     */
    public function removeAll(?int $priority = null): void;

    /**
     * Executes all callbacks as an action without return value.
     *
     * @since 1.0.0
     */
    public function dispatch(...$args): void;

    /**
     * Executes all callbacks as a filter, passing the value through each callback.
     *
     * @return mixed
     *   The filtered value after all callbacks have processed it.
     *
     * @since 1.0.0
     */
    public function filter(mixed $value, ...$args): mixed;

    /**
     * Checks whether callbacks are registered, optionally matching a specific callback or priority.
     *
     * @since 1.0.0
     */
    public function hasCallbacks(
        HookInvokableInterface|HookActionInterface|HookFilterInterface|null $callback = null,
        ?int $priority = null,
    ): bool;

    /**
     * Sorts callbacks by priority for execution order.
     *
     * @since 1.0.0
     */
    public function sort(): void;
}
