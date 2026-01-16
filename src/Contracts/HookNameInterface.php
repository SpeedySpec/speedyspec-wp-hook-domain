<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookNameInterface {
    public function getName(): string;
}
