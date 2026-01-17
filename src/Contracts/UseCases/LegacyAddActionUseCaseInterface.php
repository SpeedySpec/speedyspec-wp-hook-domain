<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyAddActionUseCaseInterface
{
    public function add(string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1): true;
}
