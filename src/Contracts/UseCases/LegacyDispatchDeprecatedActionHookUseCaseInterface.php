<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

/**
 * Use case interface for dispatching a deprecated action hook.
 *
 * ## Specification
 *
 * - As a user, given a deprecated hook name, arguments, and version, I want the action dispatched and a deprecation
 *   notice triggered.
 * - As a user, given a replacement hook name, I want the deprecation notice to inform developers of the alternative.
 * - As a user, given a custom message, I want that message included in the deprecation notice.
 * - As a user, when no callbacks are registered to the deprecated hook, I want no errors to occur.
 * - As a user, I want the deprecation notice to include the version when the hook was deprecated.
 * - As a user, I want the hook to behave like a normal action (executing callbacks in priority order) while also
 *   triggering the deprecation notice.
 *
 * @since 1.0.0
 */
interface LegacyDispatchDeprecatedActionHookUseCaseInterface
{
    /**
     * Fires a deprecated action hook and executes callbacks.
     *
     * @param string $hook_name
     *   The name of the deprecated action hook.
     * @param array $args
     *   Array of arguments passed to the action callbacks.
     * @param string $version
     *   The version of WordPress that deprecated the hook.
     * @param string $replacement
     *   Optional. The hook that should have been used instead.
     * @param string $message
     *   Optional. A message regarding the change.
     *
     * @since 1.0.0
     */
    public function dispatch(
        string $hook_name,
        array $args,
        string $version,
        string $replacement = '',
        string $message = ''
    ): void;
}
