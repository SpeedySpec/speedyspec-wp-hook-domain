<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for triggering deprecation notices when deprecated hooks are called.
 *
 * Supports the {@link do_action_deprecated()} and {@link apply_filters_deprecated()} WordPress functions by checking
 * if callbacks exist for deprecated hooks and triggering appropriate notices.
 *
 * @since 1.0.0
 */
interface CalledDeprecatedHookInterface
{
    /**
     * Triggers a deprecation notice if callbacks are registered to the deprecated hook.
     *
     * @param HookNameInterface $hook
     *   The deprecated hook being called.
     * @param string $version
     *   The version when the hook was deprecated.
     * @param string $replacement
     *   The recommended replacement hook, if any.
     * @param string $message
     *   Additional context about the deprecation.
     * @return bool
     *   True if deprecation notice was triggered, false if no callbacks were registered.
     *
     * @since 1.0.0
     */
    public function calledDeprecatedHook(
        HookNameInterface $hook,
        string $version,
        string $replacement = '',
        string $message = '',
        ...$args,
    ): bool;
}
