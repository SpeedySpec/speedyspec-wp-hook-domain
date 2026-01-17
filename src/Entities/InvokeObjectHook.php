<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Entities;

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

class InvokeObjectHook implements HookInvokableInterface
{
    private string $name;

    public function __construct(
        private object $callable,
    ) {
        $this->name = $this->getCachedName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __invoke(...$args): mixed
    {
        return ($this->callable)(...$args);
    }

    private function getCachedName(): string
    {
        if (! is_callable($this->callable)) {
            throw new HookIsNotCallableException();
        }

        $objectName = \spl_object_hash($this->callable);

        return match(true) {
            $this->callable instanceof \Closure => $objectName,
            method_exists($this->callable, '__invoke') => $objectName . '::' . '__invoke',
            default => $objectName . '::' . 'call',
        };
    }
}
