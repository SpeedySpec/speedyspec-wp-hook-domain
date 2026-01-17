<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Entities\InvokeObjectHook;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

covers(InvokeObjectHook::class);

test('implements HookInvokableInterface', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure);

    expect($hook)->toBeInstanceOf(HookInvokableInterface::class);
});

test('implements HookPriorityInterface', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure);

    expect($hook)->toBeInstanceOf(HookPriorityInterface::class);
});

test('returns default priority of 10', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure);

    expect($hook->getPriority())->toBe(10);
});

test('accepts custom priority', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure, priority: 5);

    expect($hook->getPriority())->toBe(5);
});

test('accepts negative priority for early execution', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure, priority: -100);

    expect($hook->getPriority())->toBe(-100);
});

test('accepts high priority value', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure, priority: 999);

    expect($hook->getPriority())->toBe(999);
});

test('returns object hash for closure via getName', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure);

    $name = $hook->getName();

    expect($name)->toBe(spl_object_hash($closure));
});

test('returns object hash with __invoke suffix for invokable objects', function () {
    $invokable = new InvokableTestClass();
    $hook = new InvokeObjectHook($invokable);

    $name = $hook->getName();

    expect($name)->toBe(spl_object_hash($invokable) . '::__invoke');
});

test('invokes closure with arguments', function () {
    $closure = fn(string $name) => "Hello, {$name}!";
    $hook = new InvokeObjectHook($closure);

    $result = $hook('World');

    expect($result)->toBe('Hello, World!');
});

test('invokes invokable object with arguments', function () {
    $invokable = new InvokableTestClass();
    $hook = new InvokeObjectHook($invokable);

    $result = $hook('test');

    expect($result)->toBe('invoked: test');
});

test('throws exception when object is not callable', function () {
    $nonCallable = new \stdClass();

    expect(fn() => new InvokeObjectHook($nonCallable))
        ->toThrow(HookIsNotCallableException::class);
});

test('caches name on construction', function () {
    $closure = fn() => 'test';
    $hook = new InvokeObjectHook($closure);

    $name1 = $hook->getName();
    $name2 = $hook->getName();

    expect($name1)->toBe($name2);
});

test('handles closure with multiple arguments', function () {
    $closure = fn(int $a, int $b, int $c) => $a + $b + $c;
    $hook = new InvokeObjectHook($closure);

    $result = $hook(1, 2, 3);

    expect($result)->toBe(6);
});

test('handles closure returning null', function () {
    $closure = fn() => null;
    $hook = new InvokeObjectHook($closure);

    $result = $hook();

    expect($result)->toBeNull();
});

test('handles closure with no arguments', function () {
    $closure = fn() => 'no args';
    $hook = new InvokeObjectHook($closure);

    $result = $hook();

    expect($result)->toBe('no args');
});

test('handles invokable with state', function () {
    $invokable = new StatefulInvokableClass('prefix');
    $hook = new InvokeObjectHook($invokable);

    $result = $hook('value');

    expect($result)->toBe('prefix: value');
});

test('different closures have different names', function () {
    $closure1 = fn() => 'one';
    $closure2 = fn() => 'two';

    $hook1 = new InvokeObjectHook($closure1);
    $hook2 = new InvokeObjectHook($closure2);

    expect($hook1->getName())->not->toBe($hook2->getName());
});

test('same closure reference has same name', function () {
    $closure = fn() => 'test';

    $hook1 = new InvokeObjectHook($closure);
    $hook2 = new InvokeObjectHook($closure);

    expect($hook1->getName())->toBe($hook2->getName());
});

// Test fixtures
class InvokableTestClass
{
    public function __invoke(string $value): string
    {
        return "invoked: {$value}";
    }
}

class StatefulInvokableClass
{
    public function __construct(private string $prefix)
    {
    }

    public function __invoke(string $value): string
    {
        return "{$this->prefix}: {$value}";
    }
}
