<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Services;

use SpeedySpec\WP\Hook\Domain\Contracts\CurrentHookInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface;
use SpeedySpec\WP\Hook\Domain\ValueObject\StringHookName;

/**
 * Tracks the current hook execution stack for the request.
 *
 * Provides the backing store for {@link current_filter()}, {@link doing_filter()}, and related WordPress functions.
 * Maintains both hook and callback stacks to enable debugging and nested hook detection.
 *
 * @since 1.0.0
 */
class CurrentHookService implements CurrentHookInterface
{
    /**
     * @param string[] $hooks
     *   Initial hook stack for testing.
     * @param array<string, string[]> $callbacks
     *   Initial callback stacks for testing.
     *
     * @since 1.0.0
     */
    public function __construct(
        private array $hooks = [],
        private array $callbacks = [],
    ) {
    }

    /**
     * @since 1.0.0
     */
    public function addHook(string $name): void
    {
        $this->hooks[] = $name;
    }

    /**
     * @since 1.0.0
     */
    public function removeHook(): void
    {
        if (empty($this->hooks)) {
            return;
        }
        array_pop($this->hooks);
    }

    /**
     * @since 1.0.0
     */
    public function getCurrentHook(): ?HookNameInterface
    {
        if (empty($this->hooks)) {
            return null;
        }
        return new StringHookName(end($this->hooks));
    }

    /**
     * @since 1.0.0
     */
    public function hookTraceback(): array
    {
        return $this->hooks;
    }

    /**
     * @since 1.0.0
     */
    public function addCallback(string $name): void
    {
        $this->callbacks[end($this->hooks) ?: 'unknown'][] = $name;
    }

    /**
     * @since 1.0.0
     */
    public function removeCallback(): void
    {
        array_pop($this->callbacks[end($this->hooks) ?: 'unknown']);
    }

    /**
     * @since 1.0.0
     */
    public function getCurrentCallback(): ?string
    {
        $callbacks = $this->callbacks[end($this->hooks) ?: 'unknown'] ?? [];

        if (empty($callbacks)) {
            return null;
        }

        return end($callbacks);
    }

    /**
     * @since 1.0.0
     */
    public function callbackTraceback(): array
    {
        return $this->callbacks[end($this->hooks) ?: 'unknown'];
    }

    /**
     * @since 1.0.0
     */
    public function entireCallbackTraceback(): array
    {
        return $this->callbacks;
    }
}
