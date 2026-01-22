# CLAUDE.md

This file provides guidance to Claude Code when working with this package.

## Package Overview

**Package:** `speedyspec/speedyspec-wp-hook-domain`

This is the **Domain Layer** of a Domain-Driven Design (DDD) architecture for the WordPress Hook API. It contains the core business logic, contracts (interfaces), entities, value objects, and domain services that define the hook system's behavior.

## Technology Stack

- **PHP:** >=8.4 (uses constructor property promotion, match expressions, named arguments, union types, readonly classes)
- **Testing:** Pest 3.0 or 4.0
- **No external dependencies** (pure domain logic)

## Running Tests

**Important:** PHP 8.4 may not be your default PHP version. Use Laravel Herd or specify the PHP 8.4 path explicitly.

```bash
# Install dependencies
composer install

# Run all tests
./vendor/bin/pest

# Run specific test groups
./vendor/bin/pest --group=value-objects
./vendor/bin/pest --group=entities
./vendor/bin/pest --group=services

# Run specific test file
./vendor/bin/pest tests/Entities/InvokeObjectHookTest.php

# Run with coverage
./vendor/bin/pest --coverage
```

### Finding PHP 8.4

```bash
# Check available PHP versions via Laravel Herd (macOS)
ls "/Users/$USER/Library/Application Support/Herd/bin/" | grep php

# Common paths:
# - Herd:     "/Users/$USER/Library/Application Support/Herd/bin/php84"
# - Homebrew: /opt/homebrew/opt/php@8.4/bin/php

# Running tests with explicit PHP 8.4 path (Herd example):
"/Users/$USER/Library/Application Support/Herd/bin/php84" vendor/bin/pest

# Running composer with explicit PHP 8.4:
"/Users/$USER/Library/Application Support/Herd/bin/php84" /usr/local/bin/composer install
```

## Domain-Driven Design Context

### DDD Layer Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Presentation Layer                        │
│              (WordPress Legacy Functions API)                │
│                  functions/plugins.php                       │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Application Layer                         │
│               (Use Case Interfaces - Contracts)              │
│   LegacyAddFilterUseCaseInterface, etc.                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                 ★ DOMAIN LAYER (THIS PACKAGE) ★             │
│                                                              │
│   Contracts/       - Interfaces defining behavior            │
│   Entities/        - Hook invokable entities                 │
│   ValueObject/     - Immutable value objects                 │
│   Services/        - Domain services                         │
│   Exceptions/      - Domain-specific exceptions              │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                  Infrastructure Layer                        │
│        (speedyspec-wp-hook-infra-memory, infra-wp)          │
│   Concrete implementations of domain interfaces              │
└─────────────────────────────────────────────────────────────┘
```

### Domain Layer Responsibilities

The Domain Layer in DDD:
1. **Defines Business Rules** - Core logic independent of frameworks
2. **Declares Contracts** - Interfaces that infrastructure must implement
3. **Contains Entities** - Objects with identity and lifecycle
4. **Contains Value Objects** - Immutable objects defined by attributes
5. **Provides Domain Services** - Operations that don't belong to entities
6. **Has No Infrastructure Dependencies** - Pure PHP, no framework code

## Directory Structure

```
src/
├── Contracts/
│   ├── HookSubjectInterface.php       # Individual hook storage contract
│   ├── HookContainerInterface.php     # Hook registry contract
│   ├── HookInvokableInterface.php     # Callable hook contract
│   ├── HookPriorityInterface.php      # Priority-aware hook contract
│   ├── HookNameInterface.php          # Hook name contract
│   ├── HookActionInterface.php        # Action hook marker
│   ├── HookFilterInterface.php        # Filter hook marker
│   ├── CurrentHookInterface.php       # Current hook tracking contract
│   ├── HookRunAmountInterface.php     # Hook run count contract
│   ├── CalledDeprecatedHookInterface.php
│   ├── HookValidationInterface.php
│   └── UseCases/                      # Use case interfaces for legacy API
│       ├── LegacyAddFilterUseCaseInterface.php
│       ├── LegacyAddActionUseCaseInterface.php
│       ├── LegacyDispatchFilterHookUseCaseInterface.php
│       ├── LegacyDispatchActionHookUseCaseInterface.php
│       └── ... (18 total use case interfaces)
├── Entities/
│   ├── ObjectHookInvoke.php           # Closure/object callback entity
│   ├── ArrayHookInvoke.php            # [object, method] callback entity
│   └── StringHookInvoke.php           # Function name callback entity
├── ValueObject/
│   ├── StringHookName.php             # String-based hook name
│   └── ClassNameHookName.php          # Class-based hook name
├── Services/
│   ├── CurrentHookService.php         # Tracks current executing hook
│   └── HookRunAmountService.php       # Tracks hook execution counts
├── Exceptions/
│   └── HookIsNotCallableException.php
└── HookServiceContainer.php           # Simple DI container

functions/
└── plugins.php                        # WordPress-compatible function API

tests/
├── Pest.php                           # Pest configuration
├── ValueObject/
│   ├── StringHookNameTest.php
│   └── ClassNameHookNameTest.php
├── Entities/
│   ├── InvokeObjectHookTest.php
│   ├── InvokeArrayHookTest.php
│   └── InvokeStringHookTest.php
├── Services/
│   ├── CurrentHookServiceTest.php
│   └── HookRunAmountServiceTest.php
└── ServiceContainerRegistryTest.php
```

## Key Contracts

### HookSubjectInterface
Manages callbacks for a single hook name:
```php
interface HookSubjectInterface {
    public function add(HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;
    public function remove(HookInvokableInterface|HookActionInterface|HookFilterInterface $callback): void;
    public function removeAll(?int $priority = null): void;
    public function dispatch(...$args): void;
    public function filter(mixed $value, ...$args): mixed;
    public function hasCallbacks($callback = null, ?int $priority = null): bool;
    public function sort(): void;
}
```

### HookContainerInterface
Registry for all hooks:
```php
interface HookContainerInterface {
    public function add(HookNameInterface $name, HookInvokableInterface|... $callback): void;
    public function remove(HookNameInterface $hook, HookInvokableInterface|... $callback): void;
    public function removeAll(HookNameInterface $hook, ?int $priority = null): void;
    public function dispatch(HookNameInterface $hook, ...$args): void;
    public function filter(HookNameInterface $hook, mixed $value, ...$args): mixed;
    public function hasCallbacks(HookNameInterface $hook, $callback = null, ?int $priority = null): bool;
}
```

### HookInvokableInterface & HookPriorityInterface
```php
interface HookInvokableInterface {
    public function getName(): string;
    public function __invoke(...$args): mixed;
}

interface HookPriorityInterface {
    public function getPriority(): int;
}
```

## Key Entities

### ObjectHookInvoke
For closures and invokable objects:
```php
$callback = new ObjectHookInvoke(
    callable: fn($value) => strtoupper($value),
    priority: 10
);
$callback->getName();      // Returns unique hash
$callback->getPriority();  // Returns 10
$callback('hello');        // Returns 'HELLO'
```

### ArrayHookInvoke
For `[$object, 'method']` style callbacks:
```php
$callback = new ArrayHookInvoke(
    callable: [$myObject, 'myMethod'],
    priority: 5
);
```

### StringHookInvoke
For function name strings:
```php
$callback = new StringHookInvoke(
    callable: 'strtoupper',
    priority: 10
);
```

## WordPress Function API

The `functions/plugins.php` file provides WordPress-compatible functions:
- `add_filter()`, `apply_filters()`, `apply_filters_ref_array()`
- `has_filter()`, `remove_filter()`, `remove_all_filters()`
- `current_filter()`, `doing_filter()`, `did_filter()`
- `add_action()`, `do_action()`, `do_action_ref_array()`
- `has_action()`, `remove_action()`, `remove_all_actions()`
- `current_action()`, `doing_action()`, `did_action()`
- `apply_filters_deprecated()`, `do_action_deprecated()`
- `_wp_call_all_hook()`, `_wp_filter_build_unique_id()`

These functions use `HookServiceContainer` to resolve use case implementations.

## HookServiceContainer

Simple DI container for registering implementations:
```php
HookServiceContainer::getInstance()
    ->add(LegacyAddFilterUseCaseInterface::class, fn($container) => new LegacyAddFilterUseCase(...))
    ->add(LegacyDispatchFilterHookUseCaseInterface::class, fn($container) => new LegacyDispatchFilterHookUseCase(...));
```

## Testing Patterns

Tests use Pest 4 with groups:
```php
<?php

declare(strict_types=1);

use SpeedySpec\WP\Hook\Domain\Entities\ObjectHookInvoke;

covers(ObjectHookInvoke::class);

describe('ObjectHookInvoke', function () {
    test('can invoke closure callback', function () {
        $callback = new ObjectHookInvoke(fn($v) => $v . '_modified', 10);

        expect($callback('test'))->toBe('test_modified');
    });

    test('returns correct priority', function () {
        $callback = new ObjectHookInvoke(fn() => null, 5);

        expect($callback->getPriority())->toBe(5);
    });
});
```

## Coding Standards

- **Strict Types:** All files declare `strict_types=1`
- **PHP 8.4 Features:** Constructor property promotion, match expressions, union types, readonly classes
- **Namespacing:** `SpeedySpec\WP\Hook\Domain\`
- **No Framework Dependencies:** Pure PHP domain logic
- **Interface Segregation:** Small, focused interfaces

## Common Tasks

### Adding a New Entity
1. Create class in `src/Entities/`
2. Implement relevant interfaces (`HookInvokableInterface`, `HookPriorityInterface`)
3. Write Pest tests in `tests/Entities/`

### Adding a New Use Case Interface
1. Create interface in `src/Contracts/UseCases/`
2. Define the contract methods
3. Infrastructure packages implement the interface

### Modifying Contracts
When changing interfaces, update:
1. The interface in `src/Contracts/`
2. All implementations in infrastructure packages
3. Related tests in both domain and infrastructure packages