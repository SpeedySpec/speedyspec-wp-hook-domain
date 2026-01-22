<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for checking if an action hook is currently being processed.
 *
 * ## Specification
 *
 * - As a user, given no hook name argument, I want to know whether any action is currently executing (returns
 *   true/false).
 * - As a user, given a specific hook name, I want to know whether that action is anywhere in the current execution
 *   stack (returns true/false).
 * - As a user, when actions are nested, I want to be able to detect any action in the stack, not just the innermost
 *   one.
 * - As a user, when called outside of any action execution, I want false returned.
 *
 * @since 1.0.0
 */
interface LegacyDoingActionUseCaseInterface
{
    /**
     * Returns whether or not an action hook is currently being processed.
     *
     * @param string|null $name
     *   Optional. Defaults to null, which checks if any action is currently being run.
     * @return bool
     *   Whether the action is currently in the stack.
     *
     * @since 1.0.0
     */
    public function isDoingAction(?string $name = null): bool;
}
