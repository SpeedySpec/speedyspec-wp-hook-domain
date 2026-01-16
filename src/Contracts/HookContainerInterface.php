<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

use SpeedySpec\WP\Hook\Domain\ValueObject\HookInvokableOption;

interface HookContainerInterface {
    public function add( HookNameInterface $name, HookInvokableInterface $callback, HookInvokableOption $options ): void;

    public function remove( HookNameInterface $hook, HookInvokableInterface $callback, HookInvokableOption $options ): void;

    public function dispatch( HookNameInterface $hook, ...$args ): void;

    public function filter( HookNameInterface $hook, mixed $value, ...$args ): mixed;
}
