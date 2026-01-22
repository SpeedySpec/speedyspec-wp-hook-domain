<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for tracking how many times each hook has been executed.
 *
 * Supports the {@link did_action()} and {@link did_filter()} WordPress functions by maintaining execution counts
 * per hook name throughout the request lifecycle.
 *
 * @since 1.0.0
 */
interface HookRunAmountInterface
{
    /**
     * Retrieves the number of times a hook has been executed.
     *
     * @return int
     *   The execution count, or 0 if the hook has never been executed.
     *
     * @since 1.0.0
     */
    public function getRunAmount(HookNameInterface $name): int;

    /**
     * Increments the execution count for a hook.
     *
     * @since 1.0.0
     */
    public function incrementRunAmount(HookNameInterface $name): void;
}
