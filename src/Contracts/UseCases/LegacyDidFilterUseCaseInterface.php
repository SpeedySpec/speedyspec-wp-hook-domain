<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyDidFilterUseCaseInterface {
    public function didFilter(string $name): int;
}
