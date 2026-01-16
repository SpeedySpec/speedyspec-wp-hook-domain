<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Services\HookRunAmountService;
use SpeedySpec\WP\Hook\Domain\ValueObject\StringHookName;

covers(HookRunAmountService::class);

beforeEach(function () {
    $this->service = new HookRunAmountService();
});

test('returns zero for hook that has not run', function () {
    $hookName = new StringHookName('init');

    expect($this->service->getRunAmount($hookName))->toBe(0);
});

test('increments run amount for a hook', function () {
    $hookName = new StringHookName('init');

    $this->service->incrementRunAmount($hookName);

    expect($this->service->getRunAmount($hookName))->toBe(1);
});

test('increments run amount multiple times', function () {
    $hookName = new StringHookName('wp_loaded');

    $this->service->incrementRunAmount($hookName);
    $this->service->incrementRunAmount($hookName);
    $this->service->incrementRunAmount($hookName);

    expect($this->service->getRunAmount($hookName))->toBe(3);
});

test('tracks different hooks independently', function () {
    $hook1 = new StringHookName('init');
    $hook2 = new StringHookName('wp_loaded');
    $hook3 = new StringHookName('shutdown');

    $this->service->incrementRunAmount($hook1);
    $this->service->incrementRunAmount($hook1);
    $this->service->incrementRunAmount($hook2);

    expect($this->service->getRunAmount($hook1))->toBe(2)
        ->and($this->service->getRunAmount($hook2))->toBe(1)
        ->and($this->service->getRunAmount($hook3))->toBe(0);
});

test('handles hooks with same name from different instances', function () {
    $hook1 = new StringHookName('init');
    $hook2 = new StringHookName('init');

    $this->service->incrementRunAmount($hook1);

    expect($this->service->getRunAmount($hook2))->toBe(1);
});

test('handles hooks with special characters in name', function () {
    $hookName = new StringHookName('my_plugin/custom_hook');

    $this->service->incrementRunAmount($hookName);
    $this->service->incrementRunAmount($hookName);

    expect($this->service->getRunAmount($hookName))->toBe(2);
});

test('handles empty hook name', function () {
    $hookName = new StringHookName('');

    $this->service->incrementRunAmount($hookName);

    expect($this->service->getRunAmount($hookName))->toBe(1);
});

test('maintains state across multiple getRunAmount calls', function () {
    $hookName = new StringHookName('persistent_hook');

    $this->service->incrementRunAmount($hookName);

    expect($this->service->getRunAmount($hookName))->toBe(1)
        ->and($this->service->getRunAmount($hookName))->toBe(1)
        ->and($this->service->getRunAmount($hookName))->toBe(1);
});

test('handles many different hooks', function () {
    for ($i = 0; $i < 100; $i++) {
        $hookName = new StringHookName("hook_{$i}");
        $this->service->incrementRunAmount($hookName);
    }

    expect($this->service->getRunAmount(new StringHookName('hook_0')))->toBe(1)
        ->and($this->service->getRunAmount(new StringHookName('hook_99')))->toBe(1)
        ->and($this->service->getRunAmount(new StringHookName('hook_100')))->toBe(0);
});
