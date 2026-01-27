<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class HookParamAttribute
{
    public function __construct(
        public string $name,
        public string $type,
        public ?string $description = null,
    ) {
    }
}
