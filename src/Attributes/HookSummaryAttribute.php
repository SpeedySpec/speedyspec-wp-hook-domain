<?php

namespace SpeedySpec\WP\Hook\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_ALL)]
class HookSummaryAttribute
{
    public function __construct(
        public string $summary,
    ) {}
}
