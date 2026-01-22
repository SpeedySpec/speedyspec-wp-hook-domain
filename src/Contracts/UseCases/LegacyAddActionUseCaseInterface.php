<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for adding a callback to an action hook.
 *
 * ## Specification
 *
 * - As a user, given a hook name and a callback function, I want the callback to be registered so it executes when the
 *   action is dispatched.
 * - As a user, given a priority of 5, I want my callback to execute before callbacks registered with priority 10 or
 *   higher.
 * - As a user, given no priority argument, I want my callback to default to priority 10.
 * - As a user, given multiple callbacks registered at the same priority, I want them to execute in the order they were
 *   added.
 * - As a user, given an accepted_args value of 3, I want my callback to receive 3 arguments when the action is
 *   dispatched.
 * - As a user, given no accepted_args argument, I want my callback to receive 1 argument by default.
 * - As a user, I want the function to always return true to confirm registration.
 *
 * @since 1.0.0
 */
interface LegacyAddActionUseCaseInterface
{
    /**
     * Adds a callback function to an action hook.
     *
     * @param string $hook_name
     *   The name of the action to add the callback to.
     * @param callable $callback
     *   The callback to be run when the action is dispatched.
     * @param int $priority
     *   Optional. Default 10. The order in which callbacks are executed.
     * @param int $accepted_args
     *   Optional. Default 1. The number of arguments the callback accepts.
     * @return true
     *   Always returns true.
     *
     * @since 1.0.0
     */
    public function add(string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1): true;
}
