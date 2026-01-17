# Legacy API

The legacy API provides WordPress-compatible functions for working with hooks. These functions are drop-in replacements for WordPress's plugin API.

---

## Important: Never Use Anonymous Closures

**Do not use anonymous closures (lambdas) with the legacy hook API.** Anonymous closures cannot be removed with `remove_filter()` or `remove_action()`.

```php
// ❌ NEVER DO THIS - Cannot be removed!
add_filter('my_filter', function($value) {
    return $value . ' modified';
});

// ❌ NEVER DO THIS - Arrow functions are also closures!
add_filter('excerpt_length', fn($length) => 30);
```

Each closure has a unique internal identifier generated via `spl_object_hash()`. Since you cannot reference the same closure instance later, there is no way to remove it.

**Always use removable callback types:**

```php
// ✅ Named function - can be removed
function my_content_filter($value) {
    return $value . ' modified';
}
add_filter('my_filter', 'my_content_filter');
remove_filter('my_filter', 'my_content_filter'); // Works!

// ✅ Object method - can be removed
class MyPlugin {
    public function filterContent($value) {
        return $value . ' modified';
    }
}
$plugin = new MyPlugin();
add_filter('my_filter', [$plugin, 'filterContent']);
remove_filter('my_filter', [$plugin, 'filterContent']); // Works!

// ✅ Static method - can be removed
class Formatter {
    public static function format($value) {
        return strtoupper($value);
    }
}
add_filter('my_filter', ['Formatter', 'format']);
remove_filter('my_filter', ['Formatter', 'format']); // Works!
```

If you need closure-like behavior, use the **Modern API** with `ObjectHookInvoke` instead, which gives you a reference you can use for removal.

---

## Filter Functions

### add_filter()

Adds a callback function to a filter hook.

```php
function add_filter(
    string $hook_name,
    callable $callback,
    int $priority = 10,
    int $accepted_args = 1
): true
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$hook_name` | `string` | — | The name of the filter |
| `$callback` | `callable` | — | The callback to run |
| `$priority` | `int` | `10` | Execution order (lower = earlier) |
| `$accepted_args` | `int` | `1` | Number of arguments passed to callback |

**Returns:** Always `true`

**Examples:**

```php
// Simple function
add_filter('the_content', 'my_content_filter');

// With priority and args
add_filter('the_title', 'my_title_filter', 20, 2);

// Object method
add_filter('body_class', [$myPlugin, 'addBodyClasses'], 10, 2);

// Static method
add_filter('wp_mail', ['MyMailer', 'modifyMail'], 10, 1);
```

---

### apply_filters()

Calls all callback functions attached to a filter hook.

```php
function apply_filters(
    string $hook_name,
    mixed $value,
    mixed ...$args
): mixed
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$hook_name` | `string` | The name of the filter hook |
| `$value` | `mixed` | The value to filter |
| `...$args` | `mixed` | Additional arguments passed to callbacks |

**Returns:** The filtered value

**Examples:**

```php
// Simple filter
$content = apply_filters('the_content', $post->post_content);

// With additional arguments
$title = apply_filters('the_title', $post->post_title, $post->ID);

// Creating custom filters
$price = apply_filters('product_price', $base_price, $product, $quantity);
```

---

### apply_filters_ref_array()

Like `apply_filters()`, but arguments are passed as an array.

```php
function apply_filters_ref_array(
    string $hook_name,
    array $args
): mixed
```

**Example:**

```php
$args = [$value, $post, $user];
$result = apply_filters_ref_array('my_filter', $args);
```

---

### has_filter()

Checks if any filter has been registered for a hook.

```php
function has_filter(
    string $hook_name,
    callable|string|array|false $callback = false,
    int|false $priority = false
): bool|int
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$hook_name` | `string` | — | The name of the filter hook |
| `$callback` | `callable\|false` | `false` | Specific callback to check |
| `$priority` | `int\|false` | `false` | Specific priority to check |

**Returns:**
- `bool` - If no callback specified, whether any callbacks exist
- `int` - If callback specified, the priority if found
- `false` - If callback not found

**Examples:**

```php
// Check if any filters exist
if (has_filter('the_content')) {
    // Filters are registered
}

// Check for specific callback
$priority = has_filter('the_content', 'wpautop');
if ($priority !== false) {
    echo "wpautop is at priority $priority";
}

// Check specific callback at specific priority
if (has_filter('the_content', 'wpautop', 10)) {
    // wpautop is registered at priority 10
}
```

---

### remove_filter()

Removes a callback function from a filter hook.

```php
function remove_filter(
    string $hook_name,
    callable|string|array $callback,
    int $priority = 10
): bool
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$hook_name` | `string` | — | The filter hook name |
| `$callback` | `callable` | — | The callback to remove |
| `$priority` | `int` | `10` | The priority at which it was added |

**Returns:** Whether the callback existed and was removed

**Examples:**

```php
// Remove a function
remove_filter('the_content', 'wpautop');

// Remove at specific priority
remove_filter('the_content', 'my_filter', 20);

// Remove object method
remove_filter('body_class', [$myPlugin, 'addBodyClasses']);
```

---

### remove_all_filters()

Removes all callbacks from a filter hook.

```php
function remove_all_filters(
    string $hook_name,
    int|false $priority = false
): true
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$hook_name` | `string` | — | The filter hook name |
| `$priority` | `int\|false` | `false` | Only remove at this priority |

**Returns:** Always `true`

**Examples:**

```php
// Remove all filters
remove_all_filters('the_content');

// Remove only priority 10
remove_all_filters('the_content', 10);
```

---

### current_filter()

Gets the name of the currently executing filter.

```php
function current_filter(): string|false
```

**Returns:** Hook name or `false` if none executing

---

### doing_filter()

Checks if a specific filter is currently being processed.

```php
function doing_filter(?string $hook_name = null): bool
```

---

### did_filter()

Gets the number of times a filter has been applied.

```php
function did_filter(string $hook_name): int
```

---

## Action Functions

### add_action()

Adds a callback function to an action hook.

```php
function add_action(
    string $hook_name,
    callable $callback,
    int $priority = 10,
    int $accepted_args = 1
): true
```

Identical signature to `add_filter()`.

**Examples:**

```php
// WordPress hooks
add_action('init', 'my_init_function');
add_action('wp_footer', [$myPlugin, 'renderFooter']);

// With priority
add_action('save_post', 'my_save_handler', 20, 2);
```

---

### do_action()

Executes all callbacks attached to an action hook.

```php
function do_action(string $hook_name, mixed ...$args): void
```

**Examples:**

```php
// Simple action
do_action('init');

// With arguments
do_action('save_post', $post_id, $post);

// Custom action
do_action('my_plugin_activated', $user_id);
```

---

### do_action_ref_array()

Like `do_action()`, but arguments are passed as an array.

```php
function do_action_ref_array(string $hook_name, array $args): void
```

---

### has_action()

Checks if any action has been registered for a hook. Alias of `has_filter()`.

```php
function has_action(
    string $hook_name,
    callable|string|array|false $callback = false,
    int|false $priority = false
): bool|int
```

---

### remove_action()

Removes a callback from an action hook.

```php
function remove_action(
    string $hook_name,
    callable|string|array $callback,
    int $priority = 10
): bool
```

---

### remove_all_actions()

Removes all callbacks from an action hook.

```php
function remove_all_actions(
    string $hook_name,
    int|false $priority = false
): true
```

---

### current_action()

Gets the name of the currently executing action.

```php
function current_action(): string|false
```

---

### doing_action()

Checks if a specific action is currently being processed.

```php
function doing_action(?string $hook_name = null): bool
```

---

### did_action()

Gets the number of times an action has been fired.

```php
function did_action(string $hook_name): int
```

---

## Deprecated Hook Functions

### apply_filters_deprecated()

Fires a deprecated filter hook with a deprecation notice.

```php
function apply_filters_deprecated(
    string $hook_name,
    array $args,
    string $version,
    string $replacement = '',
    string $message = ''
): mixed
```

**Example:**

```php
// Migrate from old hook to new hook
return apply_filters_deprecated(
    'old_filter_name',
    [$value, $extra],
    '2.0.0',
    'new_filter_name',
    'The old filter is deprecated. Use new_filter_name instead.'
);
```

---

### do_action_deprecated()

Fires a deprecated action hook with a deprecation notice.

```php
function do_action_deprecated(
    string $hook_name,
    array $args,
    string $version,
    string $replacement = '',
    string $message = ''
): void
```

---

## Internal Functions

### _wp_call_all_hook()

Calls the 'all' hook. Internal use only.

```php
function _wp_call_all_hook(array $args): void
```

---

### _wp_filter_build_unique_id()

Builds a unique identifier for a callback. Maintained for backwards compatibility.

```php
function _wp_filter_build_unique_id(
    string $hook_name,
    callable|string|array $callback,
    int $priority
): ?string
```

**Returns:**
- Function name for string callbacks
- `ClassName::method` for static methods
- Object hash + method for object methods
- `null` if invalid

---

## Migration Guide

### From WordPress to SpeedySpec

The API is designed to be a drop-in replacement. Simply:

1. Include the functions file
2. Include the infra package
3. Include the Service Provider and execute to setup the Service Container.

```php
// Instead of WordPress loading plugin.php...
require_once 'vendor/speedyspec/speedyspec-wp-hook-domain/functions/plugins.php';

use SpeedySpec\WP\Hook\Infra\Memory\HookServiceProvider;

$hookServiceProvider = new HookServiceProvider();
$hookServiceProvider->boot(); // Called when the application is being setup.
$hookServiceProvider->register(); // Called to register the services.

// Use exactly as in WordPress
add_filter('the_content', 'my_filter');
$content = apply_filters('the_content', $raw_content);
```

### Differences from WordPress

1. **Type declarations**: Stricter types (PHP 8.4+)
2. **No global arrays**: State managed by services
3. **Dependency injection**: Implementations are pluggable
4. **No magic**: Explicit service registration required
