<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for callbacks that specify execution priority.
 *
 * Lower priority values execute before higher values. Callbacks with equal priority execute in registration order.
 * The default priority is 10 by WordPress convention.
 *
 * @since 1.0.0
 */
interface HookPriorityInterface
{
    /**
     * Retrieves the execution priority for this callback.
     *
     * @return int
     *   The priority value where lower numbers execute first.
     *
     * @since 1.0.0
     */
    public function getPriority(): int;
}
