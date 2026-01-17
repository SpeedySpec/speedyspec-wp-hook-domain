<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookActionInterface
{
    public function dispatch( ...$args ): void;
}
