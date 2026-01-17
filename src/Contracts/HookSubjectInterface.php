<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookSubjectInterface {
    public function add( HookInvokableInterface|HookActionInterface|HookFilterInterface $callback ): void;

    public function remove( HookInvokableInterface|HookActionInterface|HookFilterInterface $callback ): void;

    public function dispatch( ...$args ): void;

    public function filter( mixed $value, ...$args ): mixed;

    public function sort(): void;
}
