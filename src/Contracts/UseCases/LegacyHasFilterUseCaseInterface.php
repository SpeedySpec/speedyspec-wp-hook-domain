<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyHasFilterUseCaseInterface
{
    public function hasHook(string $hook_name, callable|false|null $callback = null, int|false|null $priority = null): bool;
}
