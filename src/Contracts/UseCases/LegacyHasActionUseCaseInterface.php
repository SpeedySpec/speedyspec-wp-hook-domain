<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for checking if an action hook has registered callbacks.
 *
 * ## Specification
 *
 * - As a user, given only a hook name, I want to know whether any callbacks are registered to that hook (returns true
 *   or false).
 * - As a user, given a hook name and a specific callback, I want to know whether that callback is registered to the
 *   hook.
 * - As a user, given a hook name, callback, and priority, I want to know whether that callback is registered at that
 *   specific priority.
 * - As a user, when checking for a specific callback that exists, I want the priority at which it is registered
 *   returned as an integer.
 * - As a user, when checking for a callback that does not exist, I want false returned.
 * - As a user, when checking a hook with no callbacks, I want false returned.
 *
 * @since 1.0.0
 */
interface LegacyHasActionUseCaseInterface
{
    /**
     * Checks if any action has been registered for a hook, or if a specific callback is registered.
     *
     * @param string $hook_name
     *   The name of the action hook.
     * @param callable|false|null $callback
     *   Optional. The callback to check for.
     * @param int|false|null $priority
     *   Optional. The priority to check at.
     * @return bool
     *   True if callbacks are registered, false otherwise.
     *
     * @since 1.0.0
     */
    public function hasHook(string $hook_name, callable|false|null $callback = null, int|false|null $priority = null): bool;
}
