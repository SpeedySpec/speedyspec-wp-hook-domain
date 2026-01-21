<?php

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface CalledDeprecatedHookInterface
{
    public function calledDeprecatedHook(
        HookNameInterface $hook,
        string $version,
        string $replacement = '',
        string $message = '',
        ...$args,
    ): bool;
}
