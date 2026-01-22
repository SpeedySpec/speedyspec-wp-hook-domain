<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for the global hook registry that manages all hooks in the system.
 *
 * Acts as a facade over multiple {@link HookSubjectInterface} instances, routing operations to the appropriate hook
 * subject based on the hook name. Infrastructure implementations should lazily create hook subjects as needed.
 *
 * @since 1.0.0
 */
interface HookContainerInterface
{
    /**
     * Registers a callback to the named hook.
     *
     * @since 1.0.0
     */
    public function add(
        HookNameInterface $name,
        HookInvokableInterface|HookActionInterface|HookFilterInterface $callback
    ): void;

    /**
     * Unregisters a callback from the named hook.
     *
     * @since 1.0.0
     */
    public function remove(
        HookNameInterface $hook,
        HookInvokableInterface|HookActionInterface|HookFilterInterface $callback
    ): void;

    /**
     * Removes all callbacks from the named hook, optionally filtered by priority.
     *
     * @since 1.0.0
     */
    public function removeAll(HookNameInterface $hook, ?int $priority = null): void;

    /**
     * Executes all callbacks on the named hook as an action.
     *
     * @since 1.0.0
     */
    public function dispatch(HookNameInterface $hook, ...$args): void;

    /**
     * Executes all callbacks on the named hook as a filter.
     *
     * @return mixed
     *   The filtered value after all callbacks have processed it.
     *
     * @since 1.0.0
     */
    public function filter(HookNameInterface $hook, mixed $value, ...$args): mixed;

    /**
     * Checks whether callbacks are registered to the named hook.
     *
     * @since 1.0.0
     */
    public function hasCallbacks(
        HookNameInterface $hook,
        HookInvokableInterface|HookActionInterface|HookFilterInterface|null $callback = null,
        ?int $priority = null,
    ): bool;
}
