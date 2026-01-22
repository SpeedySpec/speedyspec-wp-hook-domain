<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain;

/**
 * Simple dependency injection container for hook system services.
 *
 * Provides lazy instantiation of use case implementations with caching. Infrastructure packages register their
 * implementations here, and the legacy function wrappers in `functions/plugins.php` resolve them at runtime.
 *
 * @since 1.0.0
 */
final class HookServiceContainer
{
    /** @var array<class-string, callable> */
    private array $serviceProvider = [];

    /** @var array<class-string, object> */
    private array $instanceCache = [];

    private static HookServiceContainer $instance;

    private function __construct()
    {
    }

    /**
     * Retrieves the singleton container instance.
     *
     * @since 1.0.0
     */
    public static function getInstance(): self
    {
        return static::$instance ??= new self();
    }

    /**
     * Registers a service factory for the given interface.
     *
     * @param class-string $reference
     *   The interface or class name to register.
     * @param callable $registerCallback
     *   A factory that receives the container and returns the service instance.
     *
     * @since 1.0.0
     */
    public function add(string $reference, callable $registerCallback): self
    {
        $this->serviceProvider[$reference] = $registerCallback;
        return $this;
    }

    /**
     * Removes a service registration.
     *
     * @param class-string $reference
     *   The interface or class name to unregister.
     *
     * @since 1.0.0
     */
    public function remove(string $reference): self
    {
        unset($this->serviceProvider[$reference]);
        return $this;
    }

    /**
     * Resolves and returns a service instance.
     *
     * @template T of object
     * @param class-string<T> $reference
     *   The interface or class name to resolve.
     * @return T
     *   The cached or newly created service instance.
     *
     * @throws \RuntimeException
     *   When the service is not registered.
     *
     * @since 1.0.0
     */
    public function get(string $reference): object
    {
        if (!isset($this->serviceProvider[$reference])) {
            throw new \RuntimeException("Service $reference is not registered");
        }
        return $this->instanceCache[$reference] ??= ($this->serviceProvider[$reference])(static::$instance);
    }
}
