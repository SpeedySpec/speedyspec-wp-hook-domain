<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyDispatchActionHookUseCaseInterface
{
    public function filter(string $hook_name, ...$args): mixed;
}
