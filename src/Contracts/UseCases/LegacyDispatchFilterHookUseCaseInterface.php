<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for dispatching/applying filter hooks.
 *
 * ## Specification
 *
 * - As a user, given a hook name and a value, I want all registered callbacks to be executed in priority order and the
 *   final modified value returned.
 * - As a user, given a hook name with no registered callbacks, I want the original value returned unchanged.
 * - As a user, given additional arguments beyond the value, I want those arguments passed to the callbacks.
 * - As a user, when I apply a filter, I want the filter execution count to be incremented for tracking purposes.
 * - As a user, when I apply a filter, I want the hook name added to the current filter stack during execution.
 * - As a user, given an 'all' hook is registered, I want it to be called before the specific filter callbacks execute.
 * - As a user, given a hook name that doesn't exist, I want to be able to create it implicitly by calling this
 *   function.
 *
 * @since 1.0.0
 */
interface LegacyDispatchFilterHookUseCaseInterface
{
    /**
     * Applies filter callbacks to the given value and returns the filtered result.
     *
     * @param string $hook_name
     *   The name of the filter hook.
     * @param mixed $value
     *   The value to filter.
     * @param mixed ...$args
     *   Optional. Additional parameters to pass to the callback functions.
     * @return mixed
     *   The filtered value after all hooked functions are applied to it.
     *
     * @since 1.0.0
     */
    public function filter(string $hook_name, mixed $value, ...$args): mixed;
}
