<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\ValueObject\HookInvokableOption;

covers(HookInvokableOption::class);

test('creates option with priority and accepted args', function () {
    $option = new HookInvokableOption(priority: 10, acceptedArgs: 2);

    expect($option->priority)->toBe(10)
        ->and($option->acceptedArgs)->toBe(2);
});

test('is readonly', function () {
    $reflection = new ReflectionClass(HookInvokableOption::class);

    expect($reflection->isReadOnly())->toBeTrue();
});

test('accepts default WordPress priority', function () {
    $option = new HookInvokableOption(priority: 10, acceptedArgs: 1);

    expect($option->priority)->toBe(10);
});

test('accepts high priority value', function () {
    $option = new HookInvokableOption(priority: PHP_INT_MAX, acceptedArgs: 1);

    expect($option->priority)->toBe(PHP_INT_MAX);
});

test('accepts low priority value', function () {
    $option = new HookInvokableOption(priority: PHP_INT_MIN, acceptedArgs: 1);

    expect($option->priority)->toBe(PHP_INT_MIN);
});

test('accepts zero accepted args', function () {
    $option = new HookInvokableOption(priority: 10, acceptedArgs: 0);

    expect($option->acceptedArgs)->toBe(0);
});

test('accepts many accepted args', function () {
    $option = new HookInvokableOption(priority: 10, acceptedArgs: 10);

    expect($option->acceptedArgs)->toBe(10);
});

test('accepts negative priority for early execution', function () {
    $option = new HookInvokableOption(priority: -100, acceptedArgs: 1);

    expect($option->priority)->toBe(-100);
});

test('properties are publicly accessible', function () {
    $option = new HookInvokableOption(priority: 15, acceptedArgs: 3);

    expect($option)->toHaveProperty('priority')
        ->and($option)->toHaveProperty('acceptedArgs');
});
