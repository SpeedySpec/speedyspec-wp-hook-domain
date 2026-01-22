<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Entities;

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

/**
 * Wraps function name strings as hook callbacks.
 *
 * The simplest callback type, using the function name directly as the unique identifier. Validation is deferred to
 * {@link getName()} to match WordPress behavior where invalid callbacks don't error until execution.
 *
 * @see ObjectHookInvoke
 *   For closures and invokable objects.
 * @see ArrayHookInvoke
 *   For array-style callbacks like `[$object, 'method']`.
 *
 * @since 1.0.0
 */
class StringHookInvoke implements HookInvokableInterface, HookPriorityInterface
{
    /**
     * @param string $callable
     *   A function name that exists or will exist at execution time.
     * @param int $priority
     *   Execution priority where lower values run first.
     *
     * @since 1.0.0
     */
    public function __construct(
        private string $callable,
        private int $priority = 10,
    ) {
    }

    /**
     * @throws HookIsNotCallableException
     *   When the function name is not callable.
     *
     * @since 1.0.0
     */
    public function getName(): string
    {
        if (is_callable($this->callable)) {
            return $this->callable;
        }

        throw new HookIsNotCallableException();
    }

    /**
     * @since 1.0.0
     */
    public function __invoke(...$args): mixed
    {
        return \Closure::fromCallable($this->callable)(...$args);
    }

    /**
     * @since 1.0.0
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
