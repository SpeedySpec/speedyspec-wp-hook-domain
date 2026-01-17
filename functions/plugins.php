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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param string $hook_name
 *   The name of the filter hook.
 * @param array $args
 *   The arguments supplied to the functions hooked to `$hook_name`.
 * @return mixed
 *   The filtered value after all hooked functions are applied to it.
 *
 * @since 3.0.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
 *
 * @see apply_filters()
 *   This function is identical, but the arguments passed to the functions hooked to `$hook_name` are supplied using an
 *   array.
 *
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param string $hook_name
 *   The filter to remove callbacks from.
 * @param int|false $priority
 *   Optional. Default false. The priority number to remove them from.
 * @return true
 *
 * @since 2.7.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @return string|false
 *   Hook name of the current filter, false if no filter is running.
 *
 * @since 2.5.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
 */
function current_filter()
{
    return HookServiceContainer::getInstance()
        ->get( LegacyCurrentFilterUseCaseInterface::class )
        ->currentFilter() ?? false;
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param string $hook_name
 *   The name of the filter hook.
 * @return int
 *   The number of times the filter hook has been applied.
 *
 * @since 6.1.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
 *
 */
function did_filter( $hook_name )
{
    return HookServiceContainer::getInstance()
        ->get( LegacyDidFilterUseCaseInterface::class )
        ->didFilter() ?? false;
}

/**
 * Adds a callback function to an action hook.
 *
 * Actions are the hooks that the WordPress core launches at specific points
 * during execution, or when specific events occur. Plugins can specify that
 * one or more of its PHP functions are executed at these points, using the
 * Action API.
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
 * @since 0.0.1 speedyspec-wp-hook-functions
 * @since 1.2.0 WordPress
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
 * @param string $hook_name
 *   The name of the action to be executed.
 * @param mixed  ...$args
 *   Optional. Additional arguments which are passed on to the functions hooked to the action. Default empty.
 *
 * @since 1.2.0 WordPress
 * @since 5.3.0 WordPress
 *   Formalized the existing and already documented `...$arg` parameter by adding it to the function signature.
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param string $hook_name
 *   The name of the action to be executed.
 * @param array  $args
 *   The arguments supplied to the functions hooked to `$hook_name`.
 * @see do_action()
 *   This function is identical, but the arguments passed to the functions hooked to `$hook_name` are supplied using an
 *   array.
 *
 * @since 2.1.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param string $hook_name
 *   The action to remove callbacks from.
 * @param int|false $priority
 *   Optional. The priority number to remove them from. Default false.
 * @return true Always returns true.
 *
 * @since 2.7.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @return string|false
 *   Hook name of the current action, false if no action is running.
 * @since 3.9.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param string|null $hook_name
 *   Optional. Action hook to check. Defaults to null, which checks if any action is currently being run.
 * @return bool
 *   Whether the action is currently in the stack.
 *
 * @since 3.9.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param string $hook_name
 *   The name of the action hook.
 * @return int
 *   The number of times the action hook has been fired.
 * @since 2.1.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 *
 * @param string $hook_name   The name of the filter hook.
 * @param array  $args        Array of additional function arguments to be passed to apply_filters().
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $replacement Optional. The hook that should have been used. Default empty.
 * @param string $message     Optional. A message regarding the change. Default empty.
 * @return mixed The filtered value after all hooked functions are applied to it.
 * @since 4.6.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @param array $args
 *   The collected parameters from the hook that was called.
 *
 * @since 2.5.0 WordPress
 * @since 0.0.1 speedyspec-wp-hook-functions
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
 * @since 0.0.1 speedyspec-wp-hook-functions
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
