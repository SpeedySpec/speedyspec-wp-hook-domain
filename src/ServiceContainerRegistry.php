<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain;

final class ServiceContainerRegistry
{
    private array $serviceProvider = [];

    private array $instanceCache = [];

    private static ServiceContainerRegistry $instance;

    private function __construct()
    {
    }

    public static function getInstance(): self {
        return static::$instance ??= new self();
    }

    /**
     * @param class-string $reference
     *
     */
    public function add(string $reference, callable $registerCallback): self
    {
        $this->serviceProvider[$reference] = $registerCallback;
        return $this;
    }

    /**
     *
     * @param class-string $reference
     */
    public function remove(string $reference): self
    {
        unset($this->serviceProvider[$reference]);
        return $this;
    }

    /**
     * @template T
     * @param class-string<T> $reference
     * @return T
     * @throws RuntimeException
     *   When reference does not exist in the service container
     */
    public function get(string $reference): object {
        if (!isset($this->serviceProvider[$reference])) {
            throw new \RuntimeException("Service Provider $reference is not registered");
        }
        return $this->instanceCache[$reference] ??= ($this->serviceProvider[$reference])(static::$instance);
    }
}
