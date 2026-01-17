<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Entities\InvokeArrayHook;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

covers(InvokeArrayHook::class);

test('implements HookInvokableInterface', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod']);

    expect($hook)->toBeInstanceOf(HookInvokableInterface::class);
});

test('implements HookPriorityInterface', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod']);

    expect($hook)->toBeInstanceOf(HookPriorityInterface::class);
});

test('returns default priority of 10', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod']);

    expect($hook->getPriority())->toBe(10);
});

test('accepts custom priority', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod'], priority: 5);

    expect($hook->getPriority())->toBe(5);
});

test('accepts negative priority for early execution', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod'], priority: -100);

    expect($hook->getPriority())->toBe(-100);
});

test('accepts high priority value', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod'], priority: 999);

    expect($hook->getPriority())->toBe(999);
});

test('returns class and method name via getName for static methods', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod']);

    expect($hook->getName())->toBe('ArrayHookTestClass::staticMethod');
});

test('returns class and method name via getName for instance methods', function () {
    $instance = new ArrayHookTestClass();
    $hook = new InvokeArrayHook([$instance, 'instanceMethod']);

    $name = $hook->getName();

    expect($name)->toBe('ArrayHookTestClass::instanceMethod');
});

test('invokes static method with arguments', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'staticMethod']);

    $result = $hook('test');

    expect($result)->toBe('static: test');
});

test('invokes instance method with arguments', function () {
    $instance = new ArrayHookTestClass();
    $hook = new InvokeArrayHook([$instance, 'instanceMethod']);

    $result = $hook('test');

    expect($result)->toBe('instance: test');
});

test('throws exception when array is not callable on getName', function () {
    $hook = new InvokeArrayHook(['NonExistentClass', 'nonExistentMethod']);

    expect(fn() => $hook->getName())->toThrow(HookIsNotCallableException::class);
});

test('throws exception for private methods', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'privateMethod']);

    expect(fn() => $hook->getName())->toThrow(HookIsNotCallableException::class);
});

test('handles methods with multiple arguments', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'multiArgMethod']);

    $result = $hook('a', 'b', 'c');

    expect($result)->toBe('a-b-c');
});

test('handles methods with no arguments', function () {
    $hook = new InvokeArrayHook([ArrayHookTestClass::class, 'noArgMethod']);

    $result = $hook();

    expect($result)->toBe('no args');
});

test('handles methods returning different types', function () {
    $instance = new ArrayHookTestClass();

    $intHook = new InvokeArrayHook([$instance, 'returnInt']);
    $arrayHook = new InvokeArrayHook([$instance, 'returnArray']);
    $boolHook = new InvokeArrayHook([$instance, 'returnBool']);

    expect($intHook())->toBe(42)
        ->and($arrayHook())->toBe(['a', 'b', 'c'])
        ->and($boolHook())->toBeTrue();
});

// Test fixture
class ArrayHookTestClass
{
    public static function staticMethod(string $value): string
    {
        return "static: {$value}";
    }

    public function instanceMethod(string $value): string
    {
        return "instance: {$value}";
    }

    private static function privateMethod(): string
    {
        return 'private';
    }

    public static function multiArgMethod(string $a, string $b, string $c): string
    {
        return "{$a}-{$b}-{$c}";
    }

    public static function noArgMethod(): string
    {
        return 'no args';
    }

    public function returnInt(): int
    {
        return 42;
    }

    public function returnArray(): array
    {
        return ['a', 'b', 'c'];
    }

    public function returnBool(): bool
    {
        return true;
    }
}
