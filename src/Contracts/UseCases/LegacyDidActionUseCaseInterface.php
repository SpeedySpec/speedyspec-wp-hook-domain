<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for retrieving the number of times an action has been dispatched.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want to know how many times that action has been dispatched during the current
 *   request.
 * - As a user, given a hook name that has never been dispatched, I want 0 returned.
 * - As a user, I want the count to include all dispatches of the action, even if callbacks were registered or not.
 * - As a user, I want the count to persist for the entire request lifecycle.
 *
 * @since 1.0.0
 */
interface LegacyDidActionUseCaseInterface
{
    /**
     * Retrieves the number of times an action has been dispatched during the current request.
     *
     * @param string $name
     *   The name of the action hook.
     * @return int
     *   The number of times the action hook has been dispatched.
     *
     * @since 1.0.0
     */
    public function didAction(string $name): int;
}
