<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Entities;

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

class StringHookInvoke implements HookInvokableInterface, HookPriorityInterface
{
    public function __construct(
        private string $callable,
        private int $priority = 10,
    ) {
    }

    public function getName(): string
    {
        if (is_callable($this->callable)) {
            return $this->callable;
        }

        throw new HookIsNotCallableException();
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
