<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for removing a callback from an action hook.
 *
 * ## Specification
 *
 * - As a user, given a hook name, callback, and the same priority used when adding, I want the callback removed from
 *   the hook.
 * - As a user, given no priority argument, I want the function to assume priority 10 (the default).
 * - As a user, when the callback existed and was removed, I want true returned.
 * - As a user, when the callback was not found (wrong priority or not registered), I want false returned.
 * - As a user, when I remove a callback that may not exist, I want to be able to call this function safely without
 *   errors or warnings.
 * - As a user, when the last callback is removed from a hook, I want the hook entry cleaned up from the global
 *   registry.
 *
 * @since 1.0.0
 */
interface LegacyRemoveActionUseCaseInterface
{
    /**
     * Removes a callback function from an action hook.
     *
     * @param string $hook_name
     *   The action hook to which the function to be removed is hooked.
     * @param callable $callback
     *   The callback to be removed from running when the action is dispatched.
     * @param int $priority
     *   Optional. Default 10. The exact priority used when adding the original action callback.
     * @return bool
     *   Whether the function existed before it was removed.
     *
     * @since 1.0.0
     */
    public function removeHook(string $hook_name, callable $callback, int $priority = 10): bool;
}
