<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyRemoveAllActionsUseCaseInterface {
    public function removeHook(string $hook_name, int $priority = 10): true;
}
