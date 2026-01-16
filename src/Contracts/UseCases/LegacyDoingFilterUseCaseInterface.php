<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyDoingFilterUseCaseInterface {
    public function isDoingFilter(?string $name = null): bool;
}
