<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyRemoveActionUseCaseInterface {
    public function removeHook(string $hook_name, callable $callback, int $priority = 10): bool;
}
