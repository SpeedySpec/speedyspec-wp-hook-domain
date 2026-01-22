# AGENTS.md

Guidelines for AI agents working with this package.

## Package Context

This is `speedyspec/speedyspec-wp-hook-domain` - the **Domain Layer** of a DDD architecture for WordPress Hook API.

**Key Facts:**
- PHP 8.4+ required (not default on most systems)
- Pest 3.0/4.0 for testing
- Pure domain logic, no framework dependencies
- Defines contracts that infrastructure packages implement

## PHP 8.4 Requirement

**Critical:** This package requires PHP 8.4 which may not be the system default.

### Finding PHP 8.4

```bash
# List all available PHP versions via Herd (macOS)
ls "/Users/$USER/Library/Application Support/Herd/bin/" | grep php
# Output: php, php82, php82-fpm, php83, php83-fpm, php84, php84-fpm, php85, php85-fpm

# Check if php84 is available via Herd
ls -la "/Users/$USER/Library/Application Support/Herd/bin/php84" 2>/dev/null

# Check Homebrew
ls -la /opt/homebrew/opt/php@8.4/bin/php 2>/dev/null

# Check system PHP version
php -v
```

### Running Commands with PHP 8.4

```bash
# Using Herd (common on macOS)
"/Users/$USER/Library/Application Support/Herd/bin/php84" vendor/bin/pest

# Using Homebrew
/opt/homebrew/opt/php@8.4/bin/php vendor/bin/pest

# Composer install with specific PHP
"/Users/$USER/Library/Application Support/Herd/bin/php84" /usr/local/bin/composer install

# Or use Herd's composer directly with PHP 8.4:
"/Users/$USER/Library/Application Support/Herd/bin/php84" "/Users/$USER/Library/Application Support/Herd/bin/composer" install
```

## Test Writing Guidelines

### Pest 4 Structure

```php
<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Entities\ObjectHookInvoke;

covers(ObjectHookInvoke::class);

describe('ObjectHookInvoke::__invoke()', function () {
    test('invokes closure with arguments', function () {
        $callback = new ObjectHookInvoke(fn($a, $b) => $a + $b, 10);

        expect($callback(2, 3))->toBe(5);
    });
});

describe('ObjectHookInvoke::getPriority()', function () {
    test('returns constructor priority value', function () {
        $callback = new ObjectHookInvoke(fn() => null, 15);

        expect($callback->getPriority())->toBe(15);
    });

    test('defaults to priority 10', function () {
        $callback = new ObjectHookInvoke(fn() => null);

        expect($callback->getPriority())->toBe(10);
    });
});
```

### Test File Location

| Source File | Test File Location |
|-------------|-------------------|
| `src/Entities/ObjectHookInvoke.php` | `tests/Entities/InvokeObjectHookTest.php` |
| `src/ValueObject/StringHookName.php` | `tests/ValueObject/StringHookNameTest.php` |
| `src/Services/CurrentHookService.php` | `tests/Services/CurrentHookServiceTest.php` |

### Test Groups

Tests are organized by groups in `tests/Pest.php`:
- `value-objects` - Value object tests
- `entities` - Entity tests
- `services` - Service tests

## Domain Contracts

### HookSubjectInterface

Manages callbacks for a single hook. Implementation must:
- Store callbacks with their priorities
- Sort callbacks by priority (lower first)
- Execute callbacks in priority order
- Support callback removal by reference

```php
interface HookSubjectInterface {
    // Add callback - priority comes from HookPriorityInterface on callback
    public function add(HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;

    // Remove specific callback
    public function remove(HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;

    // Remove all callbacks, optionally at specific priority
    public function removeAll(?int $priority = null): void;

    // Execute as action (no return value used)
    public function dispatch(...$args): void;

    // Execute as filter (return value passed through)
    public function filter(mixed $value, ...$args): mixed;

    // Check for registered callbacks
    public function hasCallbacks(
        HookInvokableInterface|HookActionInterface|HookFilterInterface|null $callback = null,
        ?int $priority = null
    ): bool;

    // Sort callbacks by priority
    public function sort(): void;
}
```

### HookContainerInterface

Registry for all hooks. Implementation must:
- Create HookSubject instances per hook name
- Delegate operations to appropriate HookSubject
- Track hook execution for `did_action`/`did_filter`

```php
interface HookContainerInterface {
    public function add(HookNameInterface $name, HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;
    public function remove(HookNameInterface $hook, HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;
    public function removeAll(HookNameInterface $hook, ?int $priority = null): void;
    public function dispatch(HookNameInterface $hook, ...$args): void;
    public function filter(HookNameInterface $hook, mixed $value, ...$args): mixed;
    public function hasCallbacks(
        HookNameInterface $hook,
        HookInvokableInterface|HookActionInterface|HookFilterInterface|null $callback = null,
        ?int $priority = null
    ): bool;
}
```

## Entity Design

### Hook Invoke Entities

All hook invoke entities implement:
- `HookInvokableInterface` - `getName()` and `__invoke()`
- `HookPriorityInterface` - `getPriority()`

Priority is stored in the entity, not passed separately.

```php
// Creating callbacks with priority
$callback = new ObjectHookInvoke(fn($v) => $v * 2, priority: 5);
$callback = new ArrayHookInvoke([$obj, 'method'], priority: 10);
$callback = new StringHookInvoke('strtoupper', priority: 15);

// Getting priority from callback
$priority = $callback->getPriority();
```

### Name Generation

Each entity generates a unique name for identification:
- `ObjectHookInvoke`: Uses `spl_object_hash()`
- `ArrayHookInvoke`: Uses `ClassName::methodName`
- `StringHookInvoke`: Uses the function name directly

## Common Mistakes to Avoid

1. **Wrong PHP version** - Always verify PHP 8.4 is being used
2. **Missing priority parameter** - Priority is on the callback entity, not passed to add()
3. **Wrong test directory** - Domain tests go in this package, infrastructure tests go in infra-memory/infra-wp
4. **Breaking interface changes** - Changes here affect all infrastructure implementations
5. **removeHook/removeCallback arguments** - These methods take NO arguments; they use stack-based (LIFO) removal:
   ```php
   // Correct - stack-based removal (pops the last added)
   $service->addHook('init');
   $service->addHook('wp_loaded');
   $service->removeHook();  // Removes 'wp_loaded'

   // WRONG - do not pass arguments
   $service->removeHook('wp_loaded');  // This is incorrect!
   ```

## Related Packages

- `speedyspec/speedyspec-wp-hook-infra-memory` - In-memory implementation
- `speedyspec/speedyspec-wp-hook-infra-wp` - WordPress implementation
- `speedyspec/speedyspec-wp-hook-legacy` - Legacy compatibility layer

## Quick Reference

```bash
# Install
composer install

# Test
./vendor/bin/pest

# Test specific group
./vendor/bin/pest --group=entities

# Test with PHP 8.4 explicitly
"/Users/$USER/Library/Application Support/Herd/bin/php84" vendor/bin/pest
```