<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

/**
 * Contract for tracking the current hook execution stack.
 *
 * Maintains a stack of currently executing hooks to support {@link current_filter()}, {@link doing_filter()}, and
 * related WordPress functions. The stack enables detection of nested hook calls where one callback triggers another
 * hook.
 *
 * @since 1.0.0
 */
interface CurrentHookInterface
{
    /**
     * Pushes a hook onto the execution stack.
     *
     * @since 1.0.0
     */
    public function addHook(string $name): void;

    /**
     * Pops the current hook from the execution stack.
     *
     * @since 1.0.0
     */
    public function removeHook(): void;

    /**
     * Retrieves the currently executing hook.
     *
     * @return HookNameInterface|null
     *   The innermost hook in the stack, or null if no hook is executing.
     *
     * @since 1.0.0
     */
    public function getCurrentHook(): ?HookNameInterface;

    /**
     * Retrieves all hooks in the current execution stack.
     *
     * @return string[]
     *   Hook names in order from outermost to innermost.
     *
     * @since 1.0.0
     */
    public function hookTraceback(): array;

    /**
     * Pushes a callback identifier onto the current hook's callback stack.
     *
     * @since 1.0.0
     */
    public function addCallback(string $name): void;

    /**
     * Pops the current callback from the stack.
     *
     * @since 1.0.0
     */
    public function removeCallback(): void;

    /**
     * Retrieves the currently executing callback.
     *
     * @return string|null
     *   The callback identifier, or null if no callback is executing.
     *
     * @since 1.0.0
     */
    public function getCurrentCallback(): ?string;

    /**
     * Retrieves all callbacks in the current hook's execution stack.
     *
     * @return string[]
     *   Callback identifiers in execution order.
     *
     * @since 1.0.0
     */
    public function callbackTraceback(): array;

    /**
     * Retrieves callback tracebacks for all hooks in the stack.
     *
     * @return array<string, string[]>
     *   Map of hook names to their callback tracebacks.
     *
     * @since 1.0.0
     */
    public function entireCallbackTraceback(): array;
}
