<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\ServiceContainerRegistry;

covers(ServiceContainerRegistry::class);

test('getInstance returns singleton instance', function () {
    $instance1 = ServiceContainerRegistry::getInstance();
    $instance2 = ServiceContainerRegistry::getInstance();

    expect($instance1)->toBe($instance2);
});

test('add registers a service provider', function () {
    $registry = ServiceContainerRegistry::getInstance();

    $result = $registry->add(\stdClass::class, fn() => new \stdClass());

    expect($result)->toBe($registry);
});

test('get returns registered service', function () {
    $registry = ServiceContainerRegistry::getInstance();
    $registry->add(TestServiceClass::class, fn() => new TestServiceClass('test'));

    $service = $registry->get(TestServiceClass::class);

    expect($service)->toBeInstanceOf(TestServiceClass::class)
        ->and($service->value)->toBe('test');
});

test('get caches service instance', function () {
    $registry = ServiceContainerRegistry::getInstance();
    $callCount = 0;

    $registry->add(CachedServiceClass::class, function () use (&$callCount) {
        $callCount++;
        return new CachedServiceClass();
    });

    $service1 = $registry->get(CachedServiceClass::class);
    $service2 = $registry->get(CachedServiceClass::class);

    expect($service1)->toBe($service2)
        ->and($callCount)->toBe(1);
});

test('get throws exception when service not registered', function () {
    $registry = ServiceContainerRegistry::getInstance();

    expect(fn() => $registry->get('NonExistentServiceXYZ'))
        ->toThrow(\RuntimeException::class, 'NonExistentServiceXYZ is not registered');
});

test('remove unregisters a service provider', function () {
    $registry = ServiceContainerRegistry::getInstance();
    $registry->add(RemovableServiceClass::class, fn() => new RemovableServiceClass());

    $result = $registry->remove(RemovableServiceClass::class);

    expect($result)->toBe($registry);
    expect(fn() => $registry->get(RemovableServiceClass::class))
        ->toThrow(\RuntimeException::class);
});

test('add allows method chaining', function () {
    $registry = ServiceContainerRegistry::getInstance();

    $result = $registry
        ->add(ChainedServiceA::class, fn() => new ChainedServiceA())
        ->add(ChainedServiceB::class, fn() => new ChainedServiceB());

    expect($result)->toBe($registry);
});

test('remove allows method chaining', function () {
    $registry = ServiceContainerRegistry::getInstance();
    $registry->add(ChainRemoveA::class, fn() => new ChainRemoveA());
    $registry->add(ChainRemoveB::class, fn() => new ChainRemoveB());

    $result = $registry
        ->remove(ChainRemoveA::class)
        ->remove(ChainRemoveB::class);

    expect($result)->toBe($registry);
});

test('callback receives registry instance', function () {
    $registry = ServiceContainerRegistry::getInstance();
    $receivedRegistry = null;

    $registry->add(RegistryAwareService::class, function ($reg) use (&$receivedRegistry) {
        $receivedRegistry = $reg;
        return new RegistryAwareService();
    });

    $registry->get(RegistryAwareService::class);

    expect($receivedRegistry)->toBe($registry);
});

test('service can depend on other services', function () {
    $registry = ServiceContainerRegistry::getInstance();

    $registry->add(DependencyService::class, fn() => new DependencyService());
    $registry->add(DependentService::class, fn($reg) => new DependentService(
        $reg->get(DependencyService::class)
    ));

    $dependent = $registry->get(DependentService::class);

    expect($dependent)->toBeInstanceOf(DependentService::class)
        ->and($dependent->dependency)->toBeInstanceOf(DependencyService::class);
});

// Test fixtures
class TestServiceClass
{
    public function __construct(public string $value)
    {
    }
}

class CachedServiceClass
{
}

class RemovableServiceClass
{
}

class ChainedServiceA
{
}

class ChainedServiceB
{
}

class ChainRemoveA
{
}

class ChainRemoveB
{
}

class RegistryAwareService
{
}

class DependencyService
{
}

class DependentService
{
    public function __construct(public DependencyService $dependency)
    {
    }
}
