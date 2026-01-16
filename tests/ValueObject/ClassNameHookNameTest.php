<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface;
use SpeedySpec\WP\Hook\Domain\ValueObject\ClassNameHookName;

covers(ClassNameHookName::class);

test('implements HookNameInterface', function () {
    $hookName = new ClassNameHookName(\stdClass::class);

    expect($hookName)->toBeInstanceOf(HookNameInterface::class);
});

test('returns the class name as hook name', function () {
    $hookName = new ClassNameHookName(\stdClass::class);

    expect($hookName->getName())->toBe('stdClass');
});

test('accepts fully qualified class names', function () {
    $hookName = new ClassNameHookName(\DateTimeImmutable::class);

    expect($hookName->getName())->toBe('DateTimeImmutable');
});

test('preserves namespace in class name', function () {
    $hookName = new ClassNameHookName(ClassNameHookName::class);

    expect($hookName->getName())->toBe('SpeedySpec\WP\Hook\Domain\ValueObject\ClassNameHookName');
});

test('accepts interface as class name', function () {
    $hookName = new ClassNameHookName(HookNameInterface::class);

    expect($hookName->getName())->toBe('SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface');
});

test('works with custom namespaced classes', function () {
    $className = 'MyPlugin\\Hooks\\CustomHook';
    $hookName = new ClassNameHookName($className);

    expect($hookName->getName())->toBe('MyPlugin\\Hooks\\CustomHook');
});
