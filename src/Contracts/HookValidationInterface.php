<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookValidationInterface
{
    public function validate( ...$args ): bool;
}
