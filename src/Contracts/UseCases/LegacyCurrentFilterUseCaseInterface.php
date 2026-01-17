<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyCurrentFilterUseCaseInterface
{
    public function currentFilter(): string|false;
}
