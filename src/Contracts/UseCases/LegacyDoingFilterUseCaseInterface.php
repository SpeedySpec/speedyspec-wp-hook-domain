<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for checking if a filter hook is currently being processed.
 *
 * ## Specification
 *
 * - As a user, given no hook name argument, I want to know whether any filter is currently executing (returns
 *   true/false).
 * - As a user, given a specific hook name, I want to know whether that filter is anywhere in the current execution
 *   stack (returns true/false).
 * - As a user, when filters are nested, I want to be able to detect any filter in the stack, not just the innermost
 *   one.
 * - As a user, when called outside of any filter execution, I want false returned.
 *
 * @since 1.0.0
 */
interface LegacyDoingFilterUseCaseInterface
{
    /**
     * Returns whether or not a filter hook is currently being processed.
     *
     * @param string|null $name
     *   Optional. Defaults to null, which checks if any filter is currently being run.
     * @return bool
     *   Whether the filter is currently in the stack.
     *
     * @since 1.0.0
     */
    public function isDoingFilter(?string $name = null): bool;
}
