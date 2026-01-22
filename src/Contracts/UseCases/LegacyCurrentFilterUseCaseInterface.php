<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for retrieving the name of the currently executing filter.
 *
 * ## Specification
 *
 * - As a user, when called during filter execution, I want the name of the currently executing filter returned.
 * - As a user, when called outside of any filter execution, I want false returned.
 * - As a user, when filters are nested (a filter callback triggers another filter), I want the innermost (most recent)
 *   filter name returned.
 *
 * @since 1.0.0
 */
interface LegacyCurrentFilterUseCaseInterface
{
    /**
     * Retrieves the name of the current filter hook.
     *
     * @return string|false
     *   Hook name of the current filter, false if no filter is running.
     *
     * @since 1.0.0
     */
    public function currentFilter(): string|false;
}
