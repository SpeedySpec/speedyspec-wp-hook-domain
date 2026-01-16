<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

use ReturnTypeWillChange;

interface HookObserverInterface {
    public function dispatch(...$args): void;

    #[ReturnTypeWillChange]
    public function filter(mixed $value, ...$args): mixed;

    public function validate(...$args): bool;
}
