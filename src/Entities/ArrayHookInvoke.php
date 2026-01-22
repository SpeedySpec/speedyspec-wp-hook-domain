<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Entities;

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

/**
 * Wraps array-style callbacks as hook callbacks.
 *
 * Supports both static method references (`[ClassName::class, 'method']`) and instance method references
 * (`[$object, 'method']`). Generates identifiers in `ClassName::method` format for consistent callback matching.
 *
 * @see ObjectHookInvoke
 *   For closures and invokable objects.
 * @see StringHookInvoke
 *   For function name string callbacks.
 *
 * @since 1.0.0
 */
class ArrayHookInvoke implements HookInvokableInterface, HookPriorityInterface
{
    /**
     * @param array{0: class-string|object, 1: string} $callable
     *   An array callback in the form `[class, method]` or `[object, method]`.
     * @param int $priority
     *   Execution priority where lower values run first.
     *
     * @since 1.0.0
     */
    public function __construct(
        private array $callable,
        private int $priority = 10,
    ) {
    }

    /**
     * @throws HookIsNotCallableException
     *   When the array does not represent a valid callable.
     *
     * @since 1.0.0
     */
    public function getName(): string
    {
        if (! is_callable($this->callable)) {
            throw new HookIsNotCallableException();
        }

        $class = is_object($this->callable[0])
            ? get_class($this->callable[0])
            : $this->callable[0];

        return $class . '::' . ($this->callable[1] ?? '');
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
