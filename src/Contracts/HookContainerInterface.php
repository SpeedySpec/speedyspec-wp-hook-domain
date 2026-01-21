<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookContainerInterface
{
    public function add(
        HookNameInterface $name,
        HookInvokableInterface|HookActionInterface|HookFilterInterface $callback
    ): void;

    public function remove(
        HookNameInterface $hook,
        HookInvokableInterface|HookActionInterface|HookFilterInterface $callback
    ): void;

    public function removeAll( HookNameInterface $hook, ?int $priority = null ): void;

    public function dispatch( HookNameInterface $hook, ...$args ): void;

    public function filter( HookNameInterface $hook, mixed $value, ...$args ): mixed;

    public function hasCallbacks(
        HookNameInterface $hook,
        HookInvokableInterface|HookActionInterface|HookFilterInterface|null $callback = null,
        ?int $priority = null,
    ): bool;
}
