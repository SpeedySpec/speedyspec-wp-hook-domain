<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for retrieving the name of the currently executing action.
 *
 * ## Specification
 *
 * - As a user, when called during action execution, I want the name of the currently executing action returned.
 * - As a user, when called outside of any action execution, I want false returned.
 * - As a user, when actions are nested (an action callback triggers another action), I want the innermost (most recent)
 *   action name returned.
 *
 * @since 1.0.0
 */
interface LegacyCurrentActionUseCaseInterface
{
    /**
     * Retrieves the name of the current action hook.
     *
     * @return string|false
     *   Hook name of the current action, false if no action is running.
     *
     * @since 1.0.0
     */
    public function currentAction(): string|false;
}
