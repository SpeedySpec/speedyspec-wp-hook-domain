<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Exceptions;

/**
 * Thrown when a callback cannot be invoked.
 *
 * This exception indicates that a value provided as a hook callback is not actually callable. The hook entities throw
 * this during name generation when the underlying callable fails the `is_callable()` check.
 *
 * @since 1.0.0
 */
class HookIsNotCallableException extends \InvalidArgumentException
{
}
