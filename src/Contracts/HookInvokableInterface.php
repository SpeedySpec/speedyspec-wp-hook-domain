<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookInvokableInterface
{
    public function getName(): string;

    public function __invoke( ...$args ): mixed;
}
