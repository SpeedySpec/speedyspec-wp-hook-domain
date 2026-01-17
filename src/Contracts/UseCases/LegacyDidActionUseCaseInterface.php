<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyDidActionUseCaseInterface
{
    public function didAction(string $name): int;
}
