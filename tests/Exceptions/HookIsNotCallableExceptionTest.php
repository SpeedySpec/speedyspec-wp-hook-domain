<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Exceptions\HookIsNotCallableException;

covers(HookIsNotCallableException::class);

describe('HookIsNotCallableException', function () {
    test('extends InvalidArgumentException', function () {
        $exception = new HookIsNotCallableException();

        expect($exception)->toBeInstanceOf(\InvalidArgumentException::class);
    });

    test('can be thrown and caught', function () {
        expect(fn() => throw new HookIsNotCallableException())
            ->toThrow(HookIsNotCallableException::class);
    });

    test('accepts custom message', function () {
        $exception = new HookIsNotCallableException('Custom error message');

        expect($exception->getMessage())->toBe('Custom error message');
    });

    test('accepts custom code', function () {
        $exception = new HookIsNotCallableException('Error', 500);

        expect($exception->getCode())->toBe(500);
    });

    test('accepts previous exception', function () {
        $previous = new \RuntimeException('Previous error');
        $exception = new HookIsNotCallableException('Error', 0, $previous);

        expect($exception->getPrevious())->toBe($previous);
    });

    test('has default empty message', function () {
        $exception = new HookIsNotCallableException();

        expect($exception->getMessage())->toBe('');
    });

    test('has default code of 0', function () {
        $exception = new HookIsNotCallableException();

        expect($exception->getCode())->toBe(0);
    });

    test('can be used as type hint for catch block', function () {
        $caught = false;

        try {
            throw new HookIsNotCallableException('The callback is not callable');
        } catch (HookIsNotCallableException $e) {
            $caught = true;
        }

        expect($caught)->toBeTrue();
    });

    test('is also catchable as InvalidArgumentException', function () {
        $caught = false;

        try {
            throw new HookIsNotCallableException('The callback is not callable');
        } catch (\InvalidArgumentException $e) {
            $caught = true;
        }

        expect($caught)->toBeTrue();
    });

    test('is also catchable as Exception', function () {
        $caught = false;

        try {
            throw new HookIsNotCallableException('The callback is not callable');
        } catch (\Exception $e) {
            $caught = true;
        }

        expect($caught)->toBeTrue();
    });
});
