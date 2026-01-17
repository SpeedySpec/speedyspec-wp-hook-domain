<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookFilterInterface
{
    #[ReturnTypeWillChange]
    public function filter( mixed $value, ...$args ): mixed;
}
