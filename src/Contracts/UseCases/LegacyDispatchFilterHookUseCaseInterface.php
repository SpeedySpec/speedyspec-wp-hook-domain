<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyDispatchFilterHookUseCaseInterface
{
    public function filter(string $hook_name, mixed $value, ...$args): mixed;
}
