<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for checking if a filter hook has registered callbacks.
 *
 * ## Specification
 *
 * - As a user, given only a hook name, I want to know whether any callbacks are registered to that hook (returns
 *   true/false).
 * - As a user, given a hook name and a specific callback, I want to know the priority at which that callback is
 *   registered (returns int), or false if not registered.
 * - As a user, given a hook name, callback, and priority, I want to know whether that specific callback is registered
 *   at that exact priority (returns true/false).
 * - As a user, given a hook name that has never been used, I want false returned.
 * - As a user, when checking for a callback that may not exist, I want to be able to call this function safely without
 *   errors.
 * - As a user, I want to use the === operator when testing return values because the function may return 0 (a valid
 *   priority) which evaluates to false.
 *
 * @since 1.0.0
 */
interface LegacyHasFilterUseCaseInterface
{
    /**
     * Checks if any filter has been registered for a hook.
     *
     * @param string $hook_name
     *   The name of the filter hook.
     * @param callable|false|null $callback
     *   Optional. The callback to check for.
     * @param int|false|null $priority
     *   Optional. The specific priority at which to check for the callback.
     * @return bool
     *   Whether the hook has callbacks registered.
     *
     * @since 1.0.0
     */
    public function hasHook(string $hook_name, callable|false|null $callback = null, int|false|null $priority = null): bool;
}
