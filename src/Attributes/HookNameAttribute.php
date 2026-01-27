<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class HookNameAttribute
{
    public function __construct(
        public string $name,
        public array $params = [],
    ) {}
}
