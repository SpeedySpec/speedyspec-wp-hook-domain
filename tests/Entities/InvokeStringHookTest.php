<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Contracts\HookInvokableInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\HookPriorityInterface;
use SpeedySpec\WP\Hook\Domain\Entities\InvokeStringHook;
use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

covers(InvokeStringHook::class);

test('implements HookInvokableInterface', function () {
    $hook = new InvokeStringHook('strtoupper');

    expect($hook)->toBeInstanceOf(HookInvokableInterface::class);
});

test('implements HookPriorityInterface', function () {
    $hook = new InvokeStringHook('strtoupper');

    expect($hook)->toBeInstanceOf(HookPriorityInterface::class);
});

test('returns default priority of 10', function () {
    $hook = new InvokeStringHook('strtoupper');

    expect($hook->getPriority())->toBe(10);
});

test('accepts custom priority', function () {
    $hook = new InvokeStringHook('strtoupper', priority: 5);

    expect($hook->getPriority())->toBe(5);
});

test('accepts negative priority for early execution', function () {
    $hook = new InvokeStringHook('strtoupper', priority: -100);

    expect($hook->getPriority())->toBe(-100);
});

test('accepts high priority value', function () {
    $hook = new InvokeStringHook('strtoupper', priority: 999);

    expect($hook->getPriority())->toBe(999);
});

test('returns function name via getName', function () {
    $hook = new InvokeStringHook('strtoupper');

    expect($hook->getName())->toBe('strtoupper');
});

test('invokes the callable with arguments', function () {
    $hook = new InvokeStringHook('strtoupper');

    $result = $hook('hello');

    expect($result)->toBe('HELLO');
});

test('throws exception when callable is not valid on getName', function () {
    $hook = new InvokeStringHook('nonexistent_function_xyz');

    expect(fn() => $hook->getName())->toThrow(HookIsNotCallableException::class);
});

test('works with built-in PHP functions', function () {
    $functions = [
        ['strtolower', 'HELLO', 'hello'],
        ['ucfirst', 'hello', 'Hello'],
        ['trim', '  hello  ', 'hello'],
        ['strlen', 'hello', 5],
    ];

    foreach ($functions as [$func, $input, $expected]) {
        $hook = new InvokeStringHook($func);
        expect($hook($input))->toBe($expected);
    }
});

test('works with user-defined functions', function () {
    $hook = new InvokeStringHook('test_string_hook_custom_function');

    expect($hook->getName())->toBe('test_string_hook_custom_function')
        ->and($hook('world'))->toBe('Hello, world!');
});

test('passes multiple arguments to callable', function () {
    $hook = new InvokeStringHook('str_replace');

    $result = $hook('world', 'PHP', 'Hello world');

    expect($result)->toBe('Hello PHP');
});

test('returns null from void functions', function () {
    $hook = new InvokeStringHook('test_string_hook_void_function');

    $result = $hook();

    expect($result)->toBeNull();
});

test('accepts built-in PHP function names', function () {
    $functions = ['strtolower', 'ucfirst', 'trim', 'strlen'];

    foreach ($functions as $func) {
        $hook = new InvokeStringHook($func);
        expect($hook->getName())->toBe($func);
    }
});

// Test fixtures
function test_string_hook_custom_function(string $name): string
{
    return "Hello, {$name}!";
}

function test_string_hook_void_function(): void
{
    // Do nothing
}
