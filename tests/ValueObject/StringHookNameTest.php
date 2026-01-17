<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface;
use SpeedySpec\WP\Hook\Domain\ValueObject\StringHookName;

covers(StringHookName::class);

test('implements HookNameInterface', function () {
    $hookName = new StringHookName('init');

    expect($hookName)->toBeInstanceOf(HookNameInterface::class);
});

test('returns the hook name', function () {
    $hookName = new StringHookName('wp_loaded');

    expect($hookName->getName())->toBe('wp_loaded');
});

test('accepts any string as hook name', function () {
    $names = [
        'init',
        'wp_head',
        'the_content',
        'save_post',
        'pre_get_posts',
        'template_redirect',
    ];

    foreach ($names as $name) {
        $hookName = new StringHookName($name);
        expect($hookName->getName())->toBe($name);
    }
});

test('accepts empty string as hook name', function () {
    $hookName = new StringHookName('');

    expect($hookName->getName())->toBe('');
});

test('accepts class name string as hook name', function () {
    $hookName = new StringHookName(\stdClass::class);

    expect($hookName->getName())->toBe('stdClass');
});

test('preserves hook name with special characters', function () {
    $hookName = new StringHookName('my_plugin/custom_hook');

    expect($hookName->getName())->toBe('my_plugin/custom_hook');
});

test('preserves hook name with numbers', function () {
    $hookName = new StringHookName('hook_123_action');

    expect($hookName->getName())->toBe('hook_123_action');
});
