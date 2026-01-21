<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookSubjectInterface
{
    public function add( HookInvokableInterface|HookActionInterface|HookFilterInterface $callback ): void;

    public function remove( HookInvokableInterface|HookActionInterface|HookFilterInterface $callback ): void;

    public function removeAll(?int $priority = null): void;

    public function dispatch( ...$args ): void;

    public function filter( mixed $value, ...$args ): mixed;

    public function hasCallbacks(
        HookInvokableInterface|HookActionInterface|HookFilterInterface|null $callback = null,
        ?int $priority = null,
    ): bool;

    public function sort(): void;
}
