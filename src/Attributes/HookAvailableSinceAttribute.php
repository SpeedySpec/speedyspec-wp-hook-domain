<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class HookAvailableSinceAttribute
{
    public function __construct(
        public string $version,
        public string $package,
        public ?string $description = null,
    ) {}
}
