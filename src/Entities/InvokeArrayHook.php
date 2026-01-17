<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Entities;

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

class InvokeArrayHook implements HookInvokableInterface, HookPriorityInterface
{
    public function __construct(
        private array $callable,
        private int $priority = 10,
    ) {
    }

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

    public function __invoke(...$args): mixed
    {
        return \Closure::fromCallable($this->callable)(...$args);
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
