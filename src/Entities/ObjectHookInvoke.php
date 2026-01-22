<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Entities;

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

/**
 * Wraps closures and invokable objects as hook callbacks.
 *
 * Generates unique identifiers using {@link spl_object_hash()} to enable callback removal. For invokable objects,
 * appends the method name to distinguish between different invokable types.
 *
 * @see ArrayHookInvoke
 *   For array-style callbacks like `[$object, 'method']`.
 * @see StringHookInvoke
 *   For function name string callbacks.
 *
 * @since 1.0.0
 */
class ObjectHookInvoke implements HookInvokableInterface, HookPriorityInterface
{
    private string $name;

    /**
     * @param object $callable
     *   A closure or invokable object.
     * @param int $priority
     *   Execution priority where lower values run first.
     *
     * @throws HookIsNotCallableException
     *   When the object is not callable.
     *
     * @since 1.0.0
     */
    public function __construct(
        private object $callable,
        private int $priority = 10,
    ) {
        $this->name = $this->getCachedName();
    }

    /**
     * @since 1.0.0
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @since 1.0.0
     */
    public function __invoke(...$args): mixed
    {
        return ($this->callable)(...$args);
    }

    private function getCachedName(): string
    {
        if (! is_callable($this->callable)) {
            throw new HookIsNotCallableException();
        }

        $objectName = \spl_object_hash($this->callable);

        return match(true) {
            $this->callable instanceof \Closure => $objectName,
            method_exists($this->callable, '__invoke') => $objectName . '::' . '__invoke',
            default => $objectName . '::' . 'call',
        };
    }

    /**
     * @since 1.0.0
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
