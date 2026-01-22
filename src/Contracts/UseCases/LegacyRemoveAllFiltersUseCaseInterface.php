<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for removing all callbacks from a filter hook.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want all callbacks at all priorities removed from that hook.
 * - As a user, given a hook name and a specific priority, I want only callbacks at that priority removed.
 * - As a user, when all callbacks are removed from a hook, I want the hook entry cleaned up from the global registry.
 * - As a user, when I call this function, I want it to always return true regardless of whether any callbacks existed.
 * - As a user, given a hook name that doesn't exist, I want the function to complete without errors.
 *
 * @since 1.0.0
 */
interface LegacyRemoveAllFiltersUseCaseInterface
{
    /**
     * Removes all callback functions from a filter hook.
     *
     * @param string $hook_name
     *   The filter to remove callbacks from.
     * @param int $priority
     *   Optional. Default 10. The priority number to remove them from.
     * @return true
     *   Always returns true.
     *
     * @since 1.0.0
     */
    public function removeHook(string $hook_name, int $priority = 10): true;
}
