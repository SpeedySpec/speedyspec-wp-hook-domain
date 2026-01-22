<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for retrieving the number of times a filter has been applied.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want to know how many times that filter has been applied during the current
 *   request.
 * - As a user, given a hook name that has never been applied, I want 0 returned.
 * - As a user, I want the count to include all applications of the filter, even if callbacks were registered or not.
 * - As a user, I want the count to persist for the entire request lifecycle.
 *
 * @since 1.0.0
 */
interface LegacyDidFilterUseCaseInterface
{
    /**
     * Retrieves the number of times a filter has been applied during the current request.
     *
     * @param string $name
     *   The name of the filter hook.
     * @return int
     *   The number of times the filter hook has been applied.
     *
     * @since 1.0.0
     */
    public function didFilter(string $name): int;
}
