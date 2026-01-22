<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Contracts\HookNameInterface;
use SpeedySpec\WP\Hook\Domain\Services\CurrentHookService;

covers(CurrentHookService::class);

beforeEach(function () {
    $this->service = new CurrentHookService();
});

describe('hook tracking', function () {
    test('returns null when no hooks are active', function () {
        expect($this->service->getCurrentHook())->toBeNull();
    });

    test('returns current hook after adding', function () {
        $this->service->addHook('init');

        $current = $this->service->getCurrentHook();

        expect($current)->toBeInstanceOf(HookNameInterface::class)
            ->and($current->getName())->toBe('init');
    });

    test('returns most recent hook when multiple are added', function () {
        $this->service->addHook('init');
        $this->service->addHook('wp_loaded');
        $this->service->addHook('template_redirect');

        expect($this->service->getCurrentHook()->getName())->toBe('template_redirect');
    });

    test('returns previous hook after removing current', function () {
        $this->service->addHook('init');
        $this->service->addHook('wp_loaded');

        $this->service->removeHook();

        expect($this->service->getCurrentHook()->getName())->toBe('init');
    });

    test('returns null after removing all hooks', function () {
        $this->service->addHook('init');
        $this->service->removeHook();

        expect($this->service->getCurrentHook())->toBeNull();
    });

    test('removeHook does nothing when no hooks exist', function () {
        $this->service->removeHook();

        expect($this->service->getCurrentHook())->toBeNull();
    });
});

describe('hook traceback', function () {
    test('returns empty array when no hooks', function () {
        expect($this->service->hookTraceback())->toBe([]);
    });

    test('returns all hooks in order', function () {
        $this->service->addHook('init');
        $this->service->addHook('wp_loaded');
        $this->service->addHook('template_redirect');

        expect($this->service->hookTraceback())->toBe([
            'init',
            'wp_loaded',
            'template_redirect',
        ]);
    });

    test('reflects removed hooks', function () {
        $this->service->addHook('init');
        $this->service->addHook('wp_loaded');
        $this->service->removeHook();

        expect($this->service->hookTraceback())->toBe(['init']);
    });
});

describe('callback tracking', function () {
    test('returns null when no callback is active', function () {
        expect($this->service->getCurrentCallback())->toBeNull();
    });

    test('returns current callback after adding', function () {
        $this->service->addHook('init');
        $this->service->addCallback('my_init_function');

        expect($this->service->getCurrentCallback())->toBe('my_init_function');
    });

    test('returns most recent callback when multiple are added', function () {
        $this->service->addHook('init');
        $this->service->addCallback('callback_one');
        $this->service->addCallback('callback_two');

        expect($this->service->getCurrentCallback())->toBe('callback_two');
    });

    test('returns previous callback after removing current', function () {
        $this->service->addHook('init');
        $this->service->addCallback('callback_one');
        $this->service->addCallback('callback_two');

        $this->service->removeCallback();

        expect($this->service->getCurrentCallback())->toBe('callback_one');
    });

    test('associates callbacks with current hook', function () {
        $this->service->addHook('init');
        $this->service->addCallback('init_callback');

        $this->service->addHook('wp_loaded');
        $this->service->addCallback('loaded_callback');

        expect($this->service->getCurrentCallback())->toBe('loaded_callback');
    });
});

describe('callback traceback', function () {
    test('returns callbacks for current hook', function () {
        $this->service->addHook('init');
        $this->service->addCallback('callback_one');
        $this->service->addCallback('callback_two');

        expect($this->service->callbackTraceback())->toBe([
            'callback_one',
            'callback_two',
        ]);
    });

    test('returns entire callback traceback across all hooks', function () {
        $this->service->addHook('init');
        $this->service->addCallback('init_callback');

        $this->service->addHook('wp_loaded');
        $this->service->addCallback('loaded_callback_1');
        $this->service->addCallback('loaded_callback_2');

        $traceback = $this->service->entireCallbackTraceback();

        expect($traceback)->toHaveKey('init')
            ->and($traceback)->toHaveKey('wp_loaded')
            ->and($traceback['init'])->toBe(['init_callback'])
            ->and($traceback['wp_loaded'])->toBe(['loaded_callback_1', 'loaded_callback_2']);
    });
});

describe('edge cases', function () {
    test('handles same hook name added multiple times', function () {
        $this->service->addHook('init');
        $this->service->addHook('init');

        expect($this->service->hookTraceback())->toBe(['init', 'init']);
    });

    test('handles hooks with special characters', function () {
        $this->service->addHook('my_plugin/custom_action');

        expect($this->service->getCurrentHook()->getName())->toBe('my_plugin/custom_action');
    });

    test('constructs with initial hooks and callbacks', function () {
        $service = new CurrentHookService(
            hooks: ['init', 'wp_loaded'],
            callbacks: ['init' => ['callback1'], 'wp_loaded' => ['callback2']]
        );

        expect($service->getCurrentHook()->getName())->toBe('wp_loaded')
            ->and($service->hookTraceback())->toBe(['init', 'wp_loaded']);
    });
});
