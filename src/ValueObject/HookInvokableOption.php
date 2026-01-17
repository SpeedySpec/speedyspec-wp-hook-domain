<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\ValueObject;

readonly class HookInvokableOption
{
    public function __construct(
        public int $priority,
        public int $acceptedArgs,
    ) {
    }
}
