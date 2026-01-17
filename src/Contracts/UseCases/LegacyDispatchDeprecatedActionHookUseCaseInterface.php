<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyDispatchDeprecatedActionHookUseCaseInterface
{
    public function dispatch(string $hook_name, array $args, string $version, string $replacement = '', string $message = ''): void;
}
