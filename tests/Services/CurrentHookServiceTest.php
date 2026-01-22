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

/**
 * Specification tests for current_filter() behavior.
 *
 * ## Specification
 *
 * - As a user, when called during filter execution, I want the name of the currently executing filter returned.
 * - As a user, when called outside of any filter execution, I want false returned.
 * - As a user, when filters are nested (a filter callback triggers another filter), I want the innermost (most recent)
 *   filter name returned.
 */
describe('current_filter specification', function () {
    test('returns null when called outside of any filter execution', function () {
        // Specification: when called outside of any filter execution, return false (null in domain)
        expect($this->service->getCurrentHook())->toBeNull();
    });

    test('returns current filter name during filter execution', function () {
        // Specification: when called during filter execution, return the filter name
        $this->service->addHook('the_content');

        expect($this->service->getCurrentHook()->getName())->toBe('the_content');
    });

    test('returns innermost filter name when filters are nested', function () {
        // Specification: when filters are nested, return the innermost filter name
        $this->service->addHook('the_content');
        $this->service->addHook('the_title');
        $this->service->addHook('wp_trim_words');

        expect($this->service->getCurrentHook()->getName())->toBe('wp_trim_words');
    });

    test('returns previous filter after innermost filter completes', function () {
        // Nested filter scenario: when inner filter completes, outer filter resumes
        $this->service->addHook('the_content');
        $this->service->addHook('the_title');

        // Inner filter completes
        $this->service->removeHook();

        expect($this->service->getCurrentHook()->getName())->toBe('the_content');
    });
});

/**
 * Specification tests for doing_filter() behavior.
 *
 * ## Specification
 *
 * - As a user, given no hook name argument, I want to know whether any filter is currently executing.
 * - As a user, given a specific hook name, I want to know whether that filter is anywhere in the current execution
 *   stack.
 * - As a user, when filters are nested, I want to be able to detect any filter in the stack, not just the innermost
 *   one.
 * - As a user, when called outside of any filter execution, I want false returned.
 */
describe('doing_filter specification (via hookTraceback)', function () {
    test('hookTraceback is empty when called outside of any filter execution', function () {
        // Specification: when called outside of any filter execution, return false
        // Using hookTraceback to check if any filter is executing
        expect($this->service->hookTraceback())->toBe([])
            ->and(count($this->service->hookTraceback()) > 0)->toBeFalse();
    });

    test('hookTraceback is not empty when any filter is executing', function () {
        // Specification: given no hook name argument, know whether any filter is executing
        $this->service->addHook('the_content');

        expect(count($this->service->hookTraceback()) > 0)->toBeTrue();
    });

    test('hookTraceback contains specific hook name when that filter is executing', function () {
        // Specification: given a specific hook name, know whether it's in the execution stack
        $this->service->addHook('init');
        $this->service->addHook('the_content');
        $this->service->addHook('the_title');

        expect(in_array('the_content', $this->service->hookTraceback(), true))->toBeTrue()
            ->and(in_array('init', $this->service->hookTraceback(), true))->toBeTrue()
            ->and(in_array('the_title', $this->service->hookTraceback(), true))->toBeTrue();
    });

    test('can detect any filter in nested stack not just innermost', function () {
        // Specification: when filters are nested, detect any filter in stack
        $this->service->addHook('outer_filter');
        $this->service->addHook('middle_filter');
        $this->service->addHook('inner_filter');

        // Current hook only returns innermost
        expect($this->service->getCurrentHook()->getName())->toBe('inner_filter');

        // But hookTraceback can detect all filters in stack
        $traceback = $this->service->hookTraceback();
        expect(in_array('outer_filter', $traceback, true))->toBeTrue()
            ->and(in_array('middle_filter', $traceback, true))->toBeTrue()
            ->and(in_array('inner_filter', $traceback, true))->toBeTrue();
    });

    test('hookTraceback does not contain hook after it completes', function () {
        // Filter completes and is removed from stack
        $this->service->addHook('outer_filter');
        $this->service->addHook('inner_filter');

        // Inner filter completes
        $this->service->removeHook();

        expect(in_array('inner_filter', $this->service->hookTraceback(), true))->toBeFalse()
            ->and(in_array('outer_filter', $this->service->hookTraceback(), true))->toBeTrue();
    });

    test('can detect same hook name appearing multiple times in nested calls', function () {
        // Recursive filter scenario: same filter called within itself
        $this->service->addHook('the_content');
        $this->service->addHook('some_other_filter');
        $this->service->addHook('the_content'); // Recursive call

        $traceback = $this->service->hookTraceback();

        // Count occurrences of 'the_content' in traceback
        $count = array_count_values($traceback)['the_content'] ?? 0;
        expect($count)->toBe(2);
    });
});
