<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Entities;

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

class InvokeStringHook implements HookInvokableInterface
{
    public function __construct(
        private string $callable,
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
}
