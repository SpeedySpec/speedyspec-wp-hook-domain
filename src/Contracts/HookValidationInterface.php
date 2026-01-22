<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for validating hook arguments before callback execution.
 *
 * Enables pre-execution validation of hook arguments, allowing early rejection of invalid data before callbacks
 * process it.
 *
 * @since 1.0.0
 */
interface HookValidationInterface
{
    /**
     * Validates the arguments that will be passed to hook callbacks.
     *
     * @return bool
     *   True if arguments are valid, false otherwise.
     *
     * @since 1.0.0
     */
    public function validate(...$args): bool;
}
