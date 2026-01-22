<?php

use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyAddActionUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyAddFilterUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyCurrentActionUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyCurrentFilterUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDidActionUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDidFilterUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDispatchActionHookUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDispatchDeprecatedActionHookUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDispatchDeprecatedFilterHookUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDispatchFilterHookUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDoingActionUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyDoingFilterUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyHasActionUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyHasFilterUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyRemoveActionUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyRemoveAllActionsUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyRemoveAllFiltersUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\Contracts\UseCases\LegacyRemoveFilterUseCaseInterface;
use SpeedySpec\WP\Hook\Domain\HookServiceContainer;

/**
 * Adds a callback function to a filter hook.
 *
 * WordPress offers filter hooks to allow plugins to modify
 * various types of internal data at runtime.
 *
 * A plugin can modify data by binding a callback to a filter hook. When the filter
 * is later applied, each bound callback is run in order of priority, and given
 * the opportunity to modify a value by returning a new value.
 *
 * The following example shows how a callback function is bound to a filter hook.
 *
 * Note that `$example` is passed to the callback, (maybe) modified, then returned:
 *
 *     function example_callback( $example ) {
 *         // Maybe modify $example in some way.
 *         return $example;
 *     }
 *     add_filter( 'example_filter', 'example_callback' );
 *
 * Bound callbacks can accept from none to the total number of arguments passed as parameters
 * in the corresponding apply_filters() call.
 *
 * In other words, if an apply_filters() call passes four total arguments, callbacks bound to
 * it can accept none (the same as 1) of the arguments or up to four. The important part is that
 * the `$accepted_args` value must reflect the number of arguments the bound callback *actually*
 * opted to accept. If no arguments were accepted by the callback that is considered to be the
 * same as accepting 1 argument. For example:
 *
 *     // Filter call.
 *     $value = apply_filters( 'hook', $value, $arg2, $arg3 );
 *
 *     // Accepting zero/one arguments.
 *     function example_callback() {
 *         ...
 *         return 'some value';
 *     }
 *     add_filter( 'hook', 'example_callback' ); // Where $priority is default 10, $accepted_args is default 1.
 *
 *     // Accepting two arguments (three possible).
 *     function example_callback( $value, $arg2 ) {
 *         ...
 *         return $maybe_modified_value;
 *     }
 *     add_filter( 'hook', 'example_callback', 10, 2 ); // Where $priority is 10, $accepted_args is 2.
 *
 * *Note:* The function will return true whether or not the callback is valid.
 * It is up to you to take care. This is done for optimization purposes, so
 * everything is as quick as possible.
 *
 * ## Specification
 *
 * - As a user, given a hook name and a callback function, I want the callback to be registered so it executes when the
 *   filter is applied.
 * - As a user, given a priority of 5, I want my callback to execute before callbacks registered with priority 10 or
 *   higher.
 * - As a user, given no priority argument, I want my callback to default to priority 10.
 * - As a user, given callbacks registered at the same priority, I want them to execute in the order they were added.
 * - As a user, given an accepted_args value of 3, I want my callback to receive up to 3 arguments when the filter is
 *   applied.
 * - As a user, given no accepted_args argument, I want my callback to receive 1 argument by default.
 * - As a user, when I call this function, I want it to always return true regardless of whether the callback is valid.
 * - As a user, when I register a callback to a hook that doesn't exist yet, I want the hook to be created
 *   automatically.
 *
 * @param string $hook_name
 *   The name of the filter to add the callback to.
 * @param callable $callback
 *   The callback to be run when the filter is applied.
 * @param int $priority
 *   Optional. Default 10. Used to specify the order in which the functions associated with a particular filter are
 *   executed.
 *
 *   Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in
 *   which they were added to the filter.
 * @param int $accepted_args
 *   Optional. Default 1. The number of arguments the function accepts.
 * @return true
 *
 * @since 0.71 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function add_filter( $hook_name, $callback, $priority = 10, $accepted_args = 1 ): true
{
    return HookServiceContainer::getInstance()
        ->get( LegacyAddFilterUseCaseInterface::class )
        ->add( $hook_name, $callback, $priority, $accepted_args );
}

/**
 * Calls the callback functions that have been added to a filter hook.
 *
 * This function invokes all functions attached to filter hook `$hook_name`.
 * It is possible to create new filter hooks by simply calling this function,
 * specifying the name of the new hook using the `$hook_name` parameter.
 *
 * The function also allows for multiple additional arguments to be passed to hooks.
 *
 * Example usage:
 *
 *     // The filter callback function.
 *     function example_callback( $string, $arg1, $arg2 ) {
 *         // (maybe) modify $string.
 *         return $string;
 *     }
 *     add_filter( 'example_filter', 'example_callback', 10, 3 );
 *
 *     /*
 *      * Apply the filters by calling the 'example_callback()' function
 *      * that's hooked onto `example_filter` above.
 *      *
 *      * - 'example_filter' is the filter hook.
 *      * - 'filter me' is the value being filtered.
 *      * - $arg1 and $arg2 are the additional arguments passed to the callback.
 *     $value = apply_filters( 'example_filter', 'filter me', $arg1, $arg2 );
 *
 * ## Specification
 *
 * - As a user, given a hook name and a value, I want all registered callbacks to be executed in priority order and the
 *   final modified value returned.
 * - As a user, given a hook name with no registered callbacks, I want the original value returned unchanged.
 * - As a user, given additional arguments beyond the value, I want those arguments passed to the callbacks.
 * - As a user, when I apply a filter, I want the filter execution count to be incremented for tracking purposes.
 * - As a user, when I apply a filter, I want the hook name added to the current filter stack during execution.
 * - As a user, given an 'all' hook is registered, I want it to be called before the specific filter callbacks execute.
 * - As a user, given a hook name that doesn't exist, I want to be able to create it implicitly by calling this
 *   function.
 *
 * @param string $hook_name
 *   The name of the filter hook.
 * @param mixed $value
 *   The value to filter.
 * @param mixed ...$args
 *   Optional. Additional parameters to pass to the callback functions.
 * @return mixed
 *   The filtered value after all hooked functions are applied to it.
 *
 * @since 6.0.0 WordPress
 *   Formalized the existing and already documented `...$args` parameter by adding it to the function signature.
 * @since 0.71 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function apply_filters( $hook_name, $value, ...$args )
{
    HookServiceContainer::getInstance()
        ->get( LegacyDispatchActionHookUseCaseInterface::class )
        ->add( 'all', $value, ...$args );

    return HookServiceContainer::getInstance()
        ->get( LegacyDispatchFilterHookUseCaseInterface::class )
        ->add( $hook_name, $value, ...$args );
}

/**
 * Calls the callback functions that have been added to a filter hook, specifying arguments in an array.
 *
 * ## Specification
 *
 * - As a user, given a hook name and an array of arguments where the first element is the value to filter, I want all
 *   registered callbacks executed and the modified value returned.
 * - As a user, given a hook name with no registered callbacks, I want the first element of the arguments array
 *   returned unchanged.
 * - As a user, when I need to pass arguments by reference to filter callbacks, I want to use this function instead of
 *   apply_filters.
 * - As a user, when I apply a filter, I want the filter execution count to be incremented for tracking purposes.
 * - As a user, given an 'all' hook is registered, I want it to be called before the specific filter callbacks execute.
 *
 * @param string $hook_name
 *   The name of the filter hook.
 * @param array $args
 *   The arguments supplied to the functions hooked to `$hook_name`.
 * @return mixed
 *   The filtered value after all hooked functions are applied to it.
 *
 * @since 3.0.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 *
 * @see apply_filters()
 *   This function is identical, but the arguments passed to the functions hooked to `$hook_name` are supplied using an
 *   array.
 */
function apply_filters_ref_array( $hook_name, $args )
{
    HookServiceContainer::getInstance()
        ->get( LegacyDispatchActionHookUseCaseInterface::class )
        ->add( 'all', ...$args );

    return HookServiceContainer::getInstance()
        ->get( LegacyDispatchFilterHookUseCaseInterface::class )
        ->add( $hook_name, ...$args );
}

/**
 * Checks if any filter has been registered for a hook.
 *
 * When using the `$callback` argument, this function may return a non-boolean value that evaluates to false (e.g. 0),
 * so use the `===` operator for testing the return value.
 *
 * ## Specification
 *
 * - As a user, given only a hook name, I want to know whether any callbacks are registered to that hook (returns
 *   true/false).
 * - As a user, given a hook name and a specific callback, I want to know the priority at which that callback is
 *   registered (returns int), or false if not registered.
 * - As a user, given a hook name, callback, and priority, I want to know whether that specific callback is registered
 *   at that exact priority (returns true/false).
 * - As a user, given a hook name that has never been used, I want false returned.
 * - As a user, when checking for a callback that may not exist, I want to be able to call this function safely without
 *   errors.
 * - As a user, I want to use the === operator when testing return values because the function may return 0 (a valid
 *   priority) which evaluates to false.
 *
 * @param string $hook_name
 *   The name of the filter hook.
 * @param callable|string|array|false $callback
 *   Optional. Default false. The callback to check for.
 *
 *   This function can be called unconditionally to speculatively check a callback that may or may not exist
 * @param int|false $priority
 *   Optional. Default false. The specific priority at which to check for the callback.
 *
 * @return bool|int
 *   If `$callback` is omitted, returns boolean for whether the hook has anything registered. When checking a specific
 *   function, the priority of that hook is returned, or false if the function is not attached. If `$callback` and
 *   `$priority` are both provided, a boolean is returned for whether the specific function is registered at that
 *   priority.
 *
 * @since 2.5.0 WordPress
 * @since 6.9.0 WordPress
 *   Added the `$priority` parameter.
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function has_filter( $hook_name, $callback = false, $priority = false )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyHasFilterUseCaseInterface::class )
        ->hasHook( $hook_name, $callback, $priority );
}

/**
 * Removes a callback function from a filter hook.
 *
 * This can be used to remove default functions attached to a specific filter hook and possibly replace them with a
 * substitute.
 *
 * To remove a hook, the `$callback` and `$priority` arguments must match when the hook was added. This goes for both
 * filters and actions. No warning will be given on removal failure.
 *
 * ## Specification
 *
 * - As a user, given a hook name, callback, and the same priority used when adding, I want the callback removed from
 *   the hook.
 * - As a user, given no priority argument, I want the function to assume priority 10 (the default).
 * - As a user, when the callback existed and was removed, I want true returned.
 * - As a user, when the callback was not found (wrong priority or not registered), I want false returned.
 * - As a user, when I remove a callback that may not exist, I want to be able to call this function safely without
 *   errors or warnings.
 * - As a user, when the last callback is removed from a hook, I want the hook entry cleaned up from the global
 *   registry.
 *
 * @param string $hook_name
 *   The filter hook to which the function to be removed is hooked.
 * @param callable|string|array $callback
 *   The callback to be removed from running when the filter is applied.
 *
 *   This function can be called unconditionally to speculatively remove a callback that may or may not exist.
 * @param int $priority
 *   Optional. Default 10. The exact priority used when adding the original filter callback.
 * @return bool
 *   Whether the function existed before it was removed.
 *
 * @since 1.2.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function remove_filter( $hook_name, $callback, $priority = 10 )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyRemoveFilterUseCaseInterface::class )
        ->removeHook( $hook_name, $callback, $priority);
}

/**
 * Removes all callback functions from a filter hook.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want all callbacks at all priorities removed from that hook.
 * - As a user, given a hook name and a specific priority, I want only callbacks at that priority removed.
 * - As a user, when all callbacks are removed from a hook, I want the hook entry cleaned up from the global registry.
 * - As a user, when I call this function, I want it to always return true regardless of whether any callbacks existed.
 * - As a user, given a hook name that doesn't exist, I want the function to complete without errors.
 *
 * @param string $hook_name
 *   The filter to remove callbacks from.
 * @param int|false $priority
 *   Optional. Default false. The priority number to remove them from.
 * @return true
 *
 * @since 2.7.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function remove_all_filters( $hook_name, $priority = false )
{
    HookServiceContainer::getInstance()
        ->get( LegacyRemoveAllFiltersUseCaseInterface::class )
        ->removeHook( $hook_name, $priority);

    return true;
}

/**
 * Retrieves the name of the current filter hook.
 *
 * ## Specification
 *
 * - As a user, when called during filter execution, I want the name of the currently executing filter returned.
 * - As a user, when called outside of any filter execution, I want false returned.
 * - As a user, when filters are nested (a filter callback triggers another filter), I want the innermost (most recent)
 *   filter name returned.
 *
 * @return string|false
 *   Hook name of the current filter, false if no filter is running.
 *
 * @since 2.5.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function current_filter()
{
    return HookServiceContainer::getInstance()
        ->get( LegacyCurrentFilterUseCaseInterface::class )
        ->currentFilter();
}

/**
 * Returns regardless of whether a filter hook is currently being processed.
 *
 * The function current_filter() only returns the most recent filter being executed. {@link did_filter()} returns the
 * number of times a filter has been applied during the current request.
 *
 * This function allows detection for any filter currently being executed (regardless of whether it's the most recent
 * filter to fire, in the case of hooks called from hook callbacks) to be verified.
 *
 * ## Specification
 *
 * - As a user, given no hook name argument, I want to know whether any filter is currently executing (returns
 *   true/false).
 * - As a user, given a specific hook name, I want to know whether that filter is anywhere in the current execution
 *   stack (returns true/false).
 * - As a user, when filters are nested, I want to be able to detect any filter in the stack, not just the innermost
 *   one.
 * - As a user, when called outside of any filter execution, I want false returned.
 *
 * @param string|null $hook_name
 *   Optional. Defaults to null, which checks if any filter is currently being run.
 *
 *   Filter hook to check.
 * @return bool
 *   Whether the filter is currently in the stack.
 *
 * @see current_filter()
 * @see did_filter()
 *
 * @since 3.9.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function doing_filter( $hook_name = null )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyDoingFilterUseCaseInterface::class )
        ->isDoingFilter() ?? false;
}

/**
 * Retrieves the number of times a filter has been applied during the current request.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want to know how many times that filter has been applied during the current
 *   request.
 * - As a user, given a hook name that has never been applied, I want 0 returned.
 * - As a user, I want the count to include all applications of the filter, even if callbacks were registered or not.
 * - As a user, I want the count to persist for the entire request lifecycle.
 *
 * @param string $hook_name
 *   The name of the filter hook.
 * @return int
 *   The number of times the filter hook has been applied.
 *
 * @since 6.1.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function did_filter( $hook_name )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyDidFilterUseCaseInterface::class )
        ->didFilter();
}

/**
 * Adds a callback function to an action hook.
 *
 * Actions are the hooks that the WordPress core launches at specific points
 * during execution, or when specific events occur. Plugins can specify that
 * one or more of its PHP functions are executed at these points, using the
 * Action API.
 *
 * ## Specification
 *
 * - As a user, given a hook name and a callback function, I want the callback to be registered so it executes when the
 *   action is triggered.
 * - As a user, given a priority of 5, I want my callback to execute before callbacks registered with priority 10 or
 *   higher.
 * - As a user, given no priority argument, I want my callback to default to priority 10.
 * - As a user, given callbacks registered at the same priority, I want them to execute in the order they were added.
 * - As a user, given an accepted_args value of 3, I want my callback to receive up to 3 arguments when the action is
 *   triggered.
 * - As a user, given no accepted_args argument, I want my callback to receive 1 argument by default.
 * - As a user, when I call this function, I want it to always return true regardless of whether the callback is valid.
 * - As a user, when I register a callback to a hook that doesn't exist yet, I want the hook to be created
 *   automatically.
 *
 * @param string $hook_name
 *   The name of the action to add the callback to.
 * @param callable $callback
 *   The callback to be run when the action is called.
 * @param int $priority
 *   Optional. Used to specify the order in which the functions associated with a particular action are executed.
 *
 *   Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in
 *   which they were added to the action. Default 10.
 * @param int $accepted_args
 *   Optional. The number of arguments the function accepts. Default 1.
 * @return true
 *
 * @since 1.2.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function add_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyAddActionUseCaseInterface::class )
        ->add( $hook_name, $callback, $priority, $accepted_args );
}

/**
 * Calls the callback functions that have been added to an action hook.
 *
 * This function invokes all functions attached to action hook `$hook_name`. It is possible to create new action hooks
 * by simply calling this function, specifying the name of the new hook using the `$hook_name` parameter.
 *
 * You can pass extra arguments to the hooks, much like you can with {@link apply_filters()}.
 *
 * Example usage:
 *
 *     // The action callback function.
 *     function example_callback( $arg1, $arg2 ) {
 *         // (maybe) do something with the args.
 *     }
 *     add_action( 'example_action', 'example_callback', 10, 2 );
 *
 *     /*
 *      * Trigger the actions by calling the 'example_callback()' function
 *      * that's hooked onto `example_action` above.
 *      *
 *      * - 'example_action' is the action hook.
 *      * - $arg1 and $arg2 are the additional arguments passed to the callback.
 *     do_action( 'example_action', $arg1, $arg2 );
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want all registered callbacks to be executed in priority order.
 * - As a user, given additional arguments, I want those arguments passed to all callbacks.
 * - As a user, given no additional arguments, I want an empty string passed as the first argument for backward
 *   compatibility.
 * - As a user, when I trigger an action, I want the action execution count to be incremented for tracking purposes.
 * - As a user, when I trigger an action, I want the hook name added to the current filter stack during execution.
 * - As a user, given an 'all' hook is registered, I want it to be called before the specific action callbacks execute.
 * - As a user, given a hook name that doesn't exist, I want to be able to create it implicitly by calling this
 *   function.
 * - As a user, I want the function to return nothing (void) since actions are for side effects, not return values.
 *
 * @param string $hook_name
 *   The name of the action to be executed.
 * @param mixed  ...$args
 *   Optional. Additional arguments which are passed on to the functions hooked to the action. Default empty.
 *
 * @since 1.2.0 WordPress
 * @since 5.3.0 WordPress
 *   Formalized the existing and already documented `...$arg` parameter by adding it to the function signature.
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function do_action( $hook_name, ...$args )
{
    HookServiceContainer::getInstance()
        ->get( LegacyDispatchActionHookUseCaseInterface::class )
        ->add( 'all', ...$args );

    HookServiceContainer::getInstance()
        ->get( LegacyDispatchActionHookUseCaseInterface::class )
        ->add( $hook_name, ...$args );
}

/**
 * Calls the callback functions that have been added to an action hook, specifying arguments in an array.
 *
 * ## Specification
 *
 * - As a user, given a hook name and an array of arguments, I want all registered callbacks executed with those
 *   arguments.
 * - As a user, when I need to pass arguments by reference to action callbacks, I want to use this function instead of
 *   do_action.
 * - As a user, when I trigger an action, I want the action execution count to be incremented for tracking purposes.
 * - As a user, given an 'all' hook is registered, I want it to be called before the specific action callbacks execute.
 * - As a user, I want the function to return nothing (void) since actions are for side effects, not return values.
 *
 * @param string $hook_name
 *   The name of the action to be executed.
 * @param array  $args
 *   The arguments supplied to the functions hooked to `$hook_name`.
 * @see do_action()
 *   This function is identical, but the arguments passed to the functions hooked to `$hook_name` are supplied using an
 *   array.
 *
 * @since 2.1.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function do_action_ref_array( $hook_name, $args )
{
    HookServiceContainer::getInstance()
        ->get( LegacyDispatchActionHookUseCaseInterface::class )
        ->add( 'all', ...$args );

    HookServiceContainer::getInstance()
        ->get( LegacyDispatchActionHookUseCaseInterface::class )
        ->add( $hook_name, ...$args );
}

/**
 * Checks if any action has been registered for a hook.
 *
 * When using the `$callback` argument, this function may return a non-boolean value that evaluates to false (e.g. 0),
 * so use the `===` operator for testing the return value.
 *
 * ## Specification
 *
 * - As a user, given only a hook name, I want to know whether any callbacks are registered to that action (returns
 *   true/false).
 * - As a user, given a hook name and a specific callback, I want to know the priority at which that callback is
 *   registered (returns int), or false if not registered.
 * - As a user, given a hook name, callback, and priority, I want to know whether that specific callback is registered
 *   at that exact priority (returns true/false).
 * - As a user, given a hook name that has never been used, I want false returned.
 * - As a user, when checking for a callback that may not exist, I want to be able to call this function safely without
 *   errors.
 * - As a user, I want to use the === operator when testing return values because the function may return 0 (a valid
 *   priority) which evaluates to false.
 *
 * @param string $hook_name
 *   The name of the action hook.
 * @param callable|string|array|false $callback
 *   Optional. The callback to check for. This function can be called unconditionally to speculatively check a callback
 *   that may or may not exist. Default false.
 * @param int|false $priority
 *   Optional. The specific priority at which to check for the callback. Default false.
 * @return bool|int
 *   If `$callback` is omitted, returns boolean for whether the hook has anything registered. When checking a specific
 *   function, the priority of that hook is returned, or false if the function is not attached. If `$callback` and
 *   `$priority` are both provided, a boolean is returned for whether the specific function is registered at that priority.
 * @since 2.5.0 WordPress
 * @since 6.9.0 WordPress
 *   Added the `$priority` parameter.
 * @since 1.0.0 speedyspec-wp-hook-domain
 *
 * @see has_filter()
 *   This function is an alias of has_filter().
 */
function has_action( $hook_name, $callback = false, $priority = false )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyHasActionUseCaseInterface::class )
        ->hasHook( $hook_name, $callback, $priority);
}

/**
 * Removes a callback function from an action hook.
 *
 * This can be used to remove default functions attached to a specific action hook and possibly replace them with a
 * substitute.
 *
 * To remove a hook, the `$callback` and `$priority` arguments must match when the hook was added. This goes for both
 * filters and actions. No warning will be given on removal failure.
 *
 * ## Specification
 *
 * - As a user, given a hook name, callback, and the same priority used when adding, I want the callback removed from
 *   the action.
 * - As a user, given no priority argument, I want the function to assume priority 10 (the default).
 * - As a user, when the callback existed and was removed, I want true returned.
 * - As a user, when the callback was not found (wrong priority or not registered), I want false returned.
 * - As a user, when I remove a callback that may not exist, I want to be able to call this function safely without
 *   errors or warnings.
 *
 * @param string $hook_name
 *   The action hook to which the function to be removed is hooked.
 * @param callable|string|array $callback
 *   The name of the function which should be removed.
 *
 *   This function can be called unconditionally to speculatively remove a callback that may or may not exist.
 * @param int $priority
 *   Optional. The exact priority used when adding the original action callback. Default 10.
 * @return bool
 *   Whether the function is removed.
 *
 * @since 1.2.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function remove_action( $hook_name, $callback, $priority = 10 )
{
    HookServiceContainer::getInstance()
        ->get( LegacyRemoveActionUseCaseInterface::class )
        ->removeHook( $hook_name, $callback, $priority);

    return true;
}

/**
 * Removes all callback functions from an action hook.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want all callbacks at all priorities removed from that action.
 * - As a user, given a hook name and a specific priority, I want only callbacks at that priority removed.
 * - As a user, when I call this function, I want it to always return true regardless of whether any callbacks existed.
 * - As a user, given a hook name that doesn't exist, I want the function to complete without errors.
 *
 * @param string $hook_name
 *   The action to remove callbacks from.
 * @param int|false $priority
 *   Optional. The priority number to remove them from. Default false.
 * @return true Always returns true.
 *
 * @since 2.7.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function remove_all_actions( $hook_name, $priority = false )
{
    HookServiceContainer::getInstance()
        ->get( LegacyRemoveAllActionsUseCaseInterface::class )
        ->removeHook( $hook_name, $priority );

    return true;
}

/**
 * Retrieves the name of the current action hook.
 *
 * ## Specification
 *
 * - As a user, when called during action execution, I want the name of the currently executing action returned.
 * - As a user, when called outside of any action execution, I want false returned.
 * - As a user, when actions are nested (an action callback triggers another action), I want the innermost (most
 *   recent) action name returned.
 * - As a user, I want this function to work identically to current_filter since actions and filters share the same
 *   execution stack.
 *
 * @return string|false
 *   Hook name of the current action, false if no action is running.
 *
 * @since 3.9.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function current_action()
{
    return HookServiceContainer::getInstance()
        ->get( LegacyCurrentActionUseCaseInterface::class )
        ->currentAction() ?? false;
}

/**
 * Whether an action hook is currently being processed.
 *
 * The function current_action() only returns the most recent action being executed. {@link did_action()} returns the
 * number of times an action has been fired during the current request.
 *
 * This function allows detection for any action currently being executed (regardless of whether it's the most recent
 * action to fire, in the case of hooks called from hook callbacks) to be verified.
 *
 * ## Specification
 *
 * - As a user, given no hook name argument, I want to know whether any action is currently executing (returns
 *   true/false).
 * - As a user, given a specific hook name, I want to know whether that action is anywhere in the current execution
 *   stack (returns true/false).
 * - As a user, when actions are nested, I want to be able to detect any action in the stack, not just the innermost
 *   one.
 * - As a user, when called outside of any action execution, I want false returned.
 * - As a user, I want this function to work identically to doing_filter since actions and filters share the same
 *   execution stack.
 *
 * @param string|null $hook_name
 *   Optional. Action hook to check. Defaults to null, which checks if any action is currently being run.
 * @return bool
 *   Whether the action is currently in the stack.
 *
 * @since 3.9.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 *
 * @see current_action()
 * @see did_action()
 */
function doing_action( $hook_name = null )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyDoingActionUseCaseInterface::class )
        ->isDoingAction() ?? false;
}

/**
 * Retrieves the number of times an action has been fired during the current request.
 *
 * ## Specification
 *
 * - As a user, given a hook name, I want to know how many times that action has been triggered during the current
 *   request.
 * - As a user, given a hook name that has never been triggered, I want 0 returned.
 * - As a user, I want the count to include all executions of the action, even if no callbacks were registered.
 * - As a user, I want the count to persist for the entire request lifecycle.
 * - As a user, I want this count to be separate from filter counts (did_filter tracks filters, did_action tracks
 *   actions).
 *
 * @param string $hook_name
 *   The name of the action hook.
 * @return int
 *   The number of times the action hook has been fired.
 *
 * @since 2.1.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function did_action( $hook_name )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyDidActionUseCaseInterface::class )
        ->didAction($hook_name);
}

/**
 * Fires functions attached to a deprecated filter hook.
 *
 * When a filter hook is deprecated, the apply_filters() call is replaced with
 * apply_filters_deprecated(), which triggers a deprecation notice and then fires
 * the original filter hook.
 *
 * Note: the value and extra arguments passed to the original apply_filters() call
 * must be passed here to `$args` as an array. For example:
 *
 *     // Old filter.
 *     return apply_filters( 'wpdocs_filter', $value, $extra_arg );
 *
 *     // Deprecated.
 *     return apply_filters_deprecated( 'wpdocs_filter', array( $value, $extra_arg ), '4.9.0', 'wpdocs_new_filter' );
 *
 * ## Specification
 *
 * - As a user, given a deprecated hook name with registered callbacks, I want a deprecation notice triggered before
 *   callbacks execute.
 * - As a user, given a deprecated hook name with no registered callbacks, I want the first argument returned unchanged
 *   without triggering a deprecation notice.
 * - As a user, given a version string, I want that version included in the deprecation notice.
 * - As a user, given a replacement hook name, I want it suggested in the deprecation notice.
 * - As a user, given a custom message, I want it included in the deprecation notice.
 * - As a user, I want the filtered value returned after all callbacks have executed.
 *
 * @param string $hook_name   The name of the filter hook.
 * @param array  $args        Array of additional function arguments to be passed to apply_filters().
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $replacement Optional. The hook that should have been used. Default empty.
 * @param string $message     Optional. A message regarding the change. Default empty.
 * @return mixed The filtered value after all hooked functions are applied to it.
 *
 * @since 4.6.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 *
 * @see _deprecated_hook()
 */
function apply_filters_deprecated( $hook_name, $args, $version, $replacement = '', $message = '' )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyDispatchDeprecatedFilterHookUseCaseInterface::class )
        ->dispatch($hook_name, $args, $version, $replacement, $message);
}

/**
 * Fires functions attached to a deprecated action hook.
 *
 * When an action hook is deprecated, the do_action() call is replaced with do_action_deprecated(), which triggers a
 * deprecation notice and then fires the original hook.
 *
 * ## Specification
 *
 * - As a user, given a deprecated hook name with registered callbacks, I want a deprecation notice triggered before
 *   callbacks execute.
 * - As a user, given a deprecated hook name with no registered callbacks, I want no deprecation notice triggered and
 *   no callbacks executed.
 * - As a user, given a version string, I want that version included in the deprecation notice.
 * - As a user, given a replacement hook name, I want it suggested in the deprecation notice.
 * - As a user, given a custom message, I want it included in the deprecation notice.
 * - As a user, I want the function to return nothing (void) since actions are for side effects.
 *
 * @param string $hook_name
 *   The name of the action hook.
 * @param array $args
 *   Array of additional function arguments to be passed to do_action().
 * @param string $version
 *   The version of WordPress that deprecated the hook.
 * @param string $replacement
 *   Optional. The hook that should have been used. Default empty.
 * @param string $message
 *   Optional. A message regarding the change. Default empty.
 *
 * @since 4.6.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 *
 * @see _deprecated_hook()
 */
function do_action_deprecated( $hook_name, $args, $version, $replacement = '', $message = '' )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyDispatchDeprecatedActionHookUseCaseInterface::class )
        ->dispatch($hook_name, $args, $version, $replacement, $message);
}

/**
 * Calls the 'all' hook, which will process the functions hooked into it.
 *
 * The 'all' hook passes all arguments or parameters used for the hook, which this function was called for.
 *
 * This function is used internally for apply_filters(), do_action(), and do_action_ref_array() and is not meant to be
 * used from outside those functions. This function does not check for the existence of the all hook, so it will fail
 * unless the all hook exists prior to this function call.
 *
 * ## Specification
 *
 * - As a developer, given an array of arguments from a hook call, I want all callbacks registered to the 'all' hook
 *   executed with those arguments.
 * - As a developer, I want this function to only be called internally by apply_filters, do_action, and
 *   do_action_ref_array.
 * - As a developer, given the 'all' hook does not exist, I want the function to fail since it does not check for
 *   existence.
 * - As a developer, I want this function to enable debugging and logging of all hook executions by registering a
 *   callback to 'all'.
 *
 * @param array $args
 *   The collected parameters from the hook that was called.
 *
 * @since 2.5.0 WordPress
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function _wp_call_all_hook( $args )
{
    HookServiceContainer::getInstance()
        ->get( LegacyDispatchActionHookUseCaseInterface::class )
        ->add( 'all', ...$args );
}

/**
 * Builds a unique string ID for a hook callback function.
 *
 * Functions and static method callbacks are just returned as strings and shouldn't have any speed penalty.
 *
 * @link https://core.trac.wordpress.org/ticket/3875
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
 *
 * @param string $hook_name
 *   Unused. The name of the filter to build ID for.
 * @param callable|string|array $callback
 *   The callback to generate ID for. The callback may or may not exist.
 * @param int $priority
 *   Unused. The order in which the functions associated with a particular action are executed.
 * @return string|null
 *   Unique function ID for usage as the array key. Null if a valid `$callback` is not passed.
 *
 * @since 2.2.3 WordPress
 * @since 5.3.0 WordPress
 *    Removed workarounds for spl_object_hash(). `$hook_name` and `$priority` are no longer used, and the function always
 *    returns a string.
 * @since 1.0.0 speedyspec-wp-hook-domain
 */
function _wp_filter_build_unique_id( $hook_name, $callback, $priority )
{
    // Leaving as is since there is no clear way of improving this function. Plugins might call it, so including here to
    // maintain compatibility.
    if ( is_string( $callback ) ) {
        return $callback;
    }

    if ( is_object( $callback ) ) {
        // Closures are currently implemented as objects.
        $callback = array( $callback, '' );
    } else {
        $callback = (array) $callback;
    }

    if ( is_object( $callback[0] ) ) {
        // Object class calling.
        return spl_object_hash( $callback[0] ) . $callback[1];
    } elseif ( is_string( $callback[0] ) ) {
        // Static calling.
        return $callback[0] . '::' . $callback[1];
    }

    return null;
}
