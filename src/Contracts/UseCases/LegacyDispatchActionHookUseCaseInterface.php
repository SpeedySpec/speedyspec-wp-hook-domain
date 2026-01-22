<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for dispatching an action hook.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want all callbacks registered to that hook to be executed in priority order.
 * - As a user, given additional arguments, I want those arguments passed to each callback.
 * - As a user, given no callbacks are registered for a hook, I want the function to complete without errors.
 * - As a user, I want the action execution count to be incremented each time a hook is dispatched.
 * - As a user, given callbacks that modify global state, I want those side effects to occur.
 * - As a user, I want the 'all' hook to be executed before the specific hook if any callbacks are registered to it.
 *
 * @since 1.0.0
 */
interface LegacyDispatchActionHookUseCaseInterface
{
    /**
     * Executes the callbacks hooked to the given action hook.
     *
     * @param string $hook_name
     *   The name of the action to be executed.
     * @param mixed ...$args
     *   Additional arguments which are passed on to the callbacks hooked to the action.
     *
     * @since 1.0.0
     */
    public function dispatch(string $hook_name, ...$args): void;
}
