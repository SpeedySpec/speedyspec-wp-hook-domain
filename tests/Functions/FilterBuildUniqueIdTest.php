<?php

declare(strict_types=1);

/**
 * Tests for _wp_filter_build_unique_id function.
 *
 * ## Specification
 *
 * - As a developer, given a string callback (function name), I want that string returned unchanged as the unique ID.
 * - As a developer, given a closure or invokable object, I want a unique hash generated using spl_object_hash.
 * - As a developer, given an array callback with an object instance, I want a unique ID combining the object hash and
 *   method name.
 * - As a developer, given an array callback with a static class method, I want the ID in "ClassName::methodName"
 *   format.
 * - As a developer, given an invalid callback, I want null returned.
 * - As a developer, I understand that $hook_name and $priority parameters are unused but kept for backward
 *   compatibility.
 */

require_once dirname(__DIR__, 2) . '/functions/plugins.php';

describe('_wp_filter_build_unique_id', function () {
    describe('string callbacks', function () {
        test('returns function name unchanged for string callback', function () {
            $result = _wp_filter_build_unique_id('hook_name', 'strtoupper', 10);

            expect($result)->toBe('strtoupper');
        });

        test('returns custom function name unchanged', function () {
            $result = _wp_filter_build_unique_id('the_content', 'my_custom_filter_function', 10);

            expect($result)->toBe('my_custom_filter_function');
        });

        test('returns namespaced function name unchanged', function () {
            $result = _wp_filter_build_unique_id('init', 'MyPlugin\\Functions\\setup', 10);

            expect($result)->toBe('MyPlugin\\Functions\\setup');
        });

        test('hook_name parameter is unused for string callbacks', function () {
            $result1 = _wp_filter_build_unique_id('hook_one', 'strtoupper', 10);
            $result2 = _wp_filter_build_unique_id('hook_two', 'strtoupper', 10);

            expect($result1)->toBe($result2);
        });

        test('priority parameter is unused for string callbacks', function () {
            $result1 = _wp_filter_build_unique_id('hook', 'strtoupper', 5);
            $result2 = _wp_filter_build_unique_id('hook', 'strtoupper', 100);

            expect($result1)->toBe($result2);
        });
    });

    describe('closure callbacks', function () {
        test('returns unique hash for closure', function () {
            $closure = fn() => 'test';

            $result = _wp_filter_build_unique_id('hook_name', $closure, 10);

            expect($result)->toBe(spl_object_hash($closure));
        });

        test('different closures have different unique IDs', function () {
            $closure1 = fn() => 'one';
            $closure2 = fn() => 'two';

            $result1 = _wp_filter_build_unique_id('hook', $closure1, 10);
            $result2 = _wp_filter_build_unique_id('hook', $closure2, 10);

            expect($result1)->not->toBe($result2);
        });

        test('same closure reference has same unique ID', function () {
            $closure = fn() => 'test';

            $result1 = _wp_filter_build_unique_id('hook', $closure, 10);
            $result2 = _wp_filter_build_unique_id('hook', $closure, 10);

            expect($result1)->toBe($result2);
        });
    });

    describe('invokable object callbacks', function () {
        test('returns unique hash for invokable object', function () {
            $invokable = new class {
                public function __invoke(): string
                {
                    return 'invoked';
                }
            };

            $result = _wp_filter_build_unique_id('hook_name', $invokable, 10);

            expect($result)->toBe(spl_object_hash($invokable));
        });

        test('different invokable objects have different unique IDs', function () {
            $invokable1 = new class {
                public function __invoke(): string
                {
                    return 'one';
                }
            };
            $invokable2 = new class {
                public function __invoke(): string
                {
                    return 'two';
                }
            };

            $result1 = _wp_filter_build_unique_id('hook', $invokable1, 10);
            $result2 = _wp_filter_build_unique_id('hook', $invokable2, 10);

            expect($result1)->not->toBe($result2);
        });
    });

    describe('array callbacks with object instance', function () {
        test('returns object hash combined with method name', function () {
            $object = new FilterBuildIdTestClass();

            $result = _wp_filter_build_unique_id('hook_name', [$object, 'instanceMethod'], 10);

            expect($result)->toBe(spl_object_hash($object) . 'instanceMethod');
        });

        test('different objects have different unique IDs for same method', function () {
            $object1 = new FilterBuildIdTestClass();
            $object2 = new FilterBuildIdTestClass();

            $result1 = _wp_filter_build_unique_id('hook', [$object1, 'instanceMethod'], 10);
            $result2 = _wp_filter_build_unique_id('hook', [$object2, 'instanceMethod'], 10);

            expect($result1)->not->toBe($result2);
        });

        test('same object with different methods have different unique IDs', function () {
            $object = new FilterBuildIdTestClass();

            $result1 = _wp_filter_build_unique_id('hook', [$object, 'methodOne'], 10);
            $result2 = _wp_filter_build_unique_id('hook', [$object, 'methodTwo'], 10);

            expect($result1)->not->toBe($result2);
        });
    });

    describe('array callbacks with static class method', function () {
        test('returns ClassName::methodName format for static methods', function () {
            $result = _wp_filter_build_unique_id(
                'hook_name',
                [FilterBuildIdTestClass::class, 'staticMethod'],
                10
            );

            expect($result)->toBe('FilterBuildIdTestClass::staticMethod');
        });

        test('returns ClassName::methodName for string class reference', function () {
            $result = _wp_filter_build_unique_id(
                'hook_name',
                ['FilterBuildIdTestClass', 'staticMethod'],
                10
            );

            expect($result)->toBe('FilterBuildIdTestClass::staticMethod');
        });

        test('preserves namespace in class name', function () {
            $result = _wp_filter_build_unique_id(
                'hook_name',
                ['MyPlugin\\Hooks\\HookHandler', 'handle'],
                10
            );

            expect($result)->toBe('MyPlugin\\Hooks\\HookHandler::handle');
        });

        test('same static method reference always has same unique ID', function () {
            $result1 = _wp_filter_build_unique_id('hook', [FilterBuildIdTestClass::class, 'staticMethod'], 10);
            $result2 = _wp_filter_build_unique_id('hook', [FilterBuildIdTestClass::class, 'staticMethod'], 10);

            expect($result1)->toBe($result2);
        });
    });

    describe('invalid callbacks', function () {
        test('returns null for non-array non-string non-object callback', function () {
            // Integer callback
            $result = _wp_filter_build_unique_id('hook_name', 123, 10);

            expect($result)->toBeNull();
        });

        test('returns null for boolean callback', function () {
            $result = _wp_filter_build_unique_id('hook_name', true, 10);

            expect($result)->toBeNull();
        });

        test('returns null for float callback', function () {
            $result = _wp_filter_build_unique_id('hook_name', 3.14, 10);

            expect($result)->toBeNull();
        });

        test('returns null for resource callback', function () {
            $resource = fopen('php://memory', 'r');
            $result = _wp_filter_build_unique_id('hook_name', $resource, 10);
            fclose($resource);

            expect($result)->toBeNull();
        });
    });

    describe('backward compatibility', function () {
        test('hook_name parameter does not affect result', function () {
            $closure = fn() => 'test';

            $result1 = _wp_filter_build_unique_id('hook_a', $closure, 10);
            $result2 = _wp_filter_build_unique_id('hook_b', $closure, 10);
            $result3 = _wp_filter_build_unique_id('', $closure, 10);

            expect($result1)->toBe($result2)
                ->and($result2)->toBe($result3);
        });

        test('priority parameter does not affect result', function () {
            $closure = fn() => 'test';

            $result1 = _wp_filter_build_unique_id('hook', $closure, 1);
            $result2 = _wp_filter_build_unique_id('hook', $closure, 100);
            $result3 = _wp_filter_build_unique_id('hook', $closure, PHP_INT_MAX);

            expect($result1)->toBe($result2)
                ->and($result2)->toBe($result3);
        });
    });
});

// Test fixture
class FilterBuildIdTestClass
{
    public function instanceMethod(): string
    {
        return 'instance';
    }

    public function methodOne(): string
    {
        return 'one';
    }

    public function methodTwo(): string
    {
        return 'two';
    }

    public static function staticMethod(): string
    {
        return 'static';
    }
}
