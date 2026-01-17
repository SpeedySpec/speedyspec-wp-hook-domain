<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts\UseCases;

interface LegacyCurrentActionUseCaseInterface
{
    public function currentAction(): string|false;
}
