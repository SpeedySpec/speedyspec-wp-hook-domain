<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookPriorityInterface {
    public function getPriority(): int;
}
