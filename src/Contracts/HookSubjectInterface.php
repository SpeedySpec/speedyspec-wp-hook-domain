<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;


use SpeedySpec\WP\Hook\Domain\ValueObject\HookInvokableOption;

interface HookSubjectInterface {
    public function add( HookInvokableInterface $callback, HookInvokableOption $options ): void;

    public function remove( HookInvokableInterface $callback, HookInvokableOption $options ): void;

    public function dispatch( ...$args,): void;

    public function filter(mixed $value, ...$args): mixed;

    public function sort(): void;
}
