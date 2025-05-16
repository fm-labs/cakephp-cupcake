<?php
declare(strict_types=1);

namespace Cupcake\Hook;

class Hook
{
    public const TYPE_FILTER = 'filter';
    public const TYPE_ACTION = 'action';

    /**
     * @var array Hook callback storage
     */
    protected static array $_hooks = [];

    /**
     * Add FILTER hook.
     *
     * @param string $name Hook name.
     * @param callable $callback Hook callback.
     * @return void
     */
    public static function addFilter(string $name, callable $callback): void
    {
        static::add(static::TYPE_FILTER, $name, $callback);
    }

    /**
     * Fire FILTER hooks.
     *
     * @param string $name Hook name.
     * @param mixed $context Filter context
     * @param mixed ...$args Filter args
     * @return mixed
     */
    public static function doFilter(string $name, mixed $context, mixed ...$args): mixed
    {
        $callbacks = static::$_hooks[static::TYPE_FILTER][$name] ?? [];
        foreach ($callbacks as $callback) {
            //if (is_callable($callback)) {
            $context = call_user_func($callback, $context, $args);
            //}
        }

        return $context;
    }

    /**
     * Add ACTION hook.
     *
     * @param string $name Hook name.
     * @param callable $callback Hook callback.
     * @return void
     */
    public static function addAction(string $name, callable $callback): void
    {
        static::add(static::TYPE_ACTION, $name, $callback);
    }

    /**
     * Fire ACTION hooks.
     *
     * @param string $name Action hook name.
     * @param mixed ...$args Action hook args.
     * @return void
     */
    public static function doAction(string $name, mixed ...$args): void
    {
        $callbacks = static::$_hooks[static::TYPE_ACTION][$name] ?? [];
        foreach ($callbacks as $callback) {
            //if (is_callable($callback)) {
            call_user_func($callback, $args);
            //}
        }
    }

    /**
     * Set hooks by type.
     * It is not recommended to use this function directly.
     *
     * @internal
     * @param string $type Hook type. If NULL, all registered hooks will be reseted.
     * @param array $hooks List of callables.
     * @return void
     */
    public static function set(string $type, array $hooks = []): void
    {
        static::$_hooks[$type] = $hooks;
    }

    /**
     * Add a hook.
     *
     * @param string $type Hook type.
     * @param string $name Hook name.
     * @param callable $callback Hook callback.
     * @return void
     */
    private static function add(string $type, string $name, callable $callback): void
    {
        static::$_hooks[$type][$name][] = $callback;
    }
}
