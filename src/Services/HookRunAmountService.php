<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Services;

use SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookRunAmountInterface;

/**
 * Tracks hook execution counts for the current request.
 *
 * Provides the backing store for {@link did_action()} and {@link did_filter()} WordPress functions, counting each
 * hook dispatch regardless of whether callbacks are registered.
 *
 * @since 1.0.0
 */
class HookRunAmountService implements HookRunAmountInterface
{
    /**
     * @var array<string, int>
     *   Map of hook names to execution counts.
     */
    private array $hooksRunAmount = [];

    /**
     * @since 1.0.0
     */
    public function getRunAmount(HookNameInterface $name): int
    {
        return $this->hooksRunAmount[$name->getName()] ?? 0;
    }

    /**
     * @since 1.0.0
     */
    public function incrementRunAmount(HookNameInterface $name): void
    {
        $this->hooksRunAmount[$name->getName()] ??= 0;
        $this->hooksRunAmount[$name->getName()]++;
    }
}
