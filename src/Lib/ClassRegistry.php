<?php
declare(strict_types=1);

namespace Cupcake\Lib;

use Cupcake\Exception\ClassNotFoundException;
use Cake\Core\App;
use Cake\Log\Log;

/**
 * Class ClassRegistry
 *
 * Register class-alias for class locations in namespaces
 *
 * @package Cupcake\Lib
 */
class ClassRegistry
{
    /**
     * @var array
     */
    protected static $_classes = [];

    /**
     * @var array
     */
    protected static $_factories = [];

    /**
     * @var array
     */
    protected static $_instances = [];

    /**
     * @param $type
     * @param callable $factory
     */
    public static function setFactory($type, callable $factory)
    {
        self::$_factories[$type] = $factory;
    }

    /**
     * Register class in namespace
     *
     * @param $type
     * @param $key
     * @param null $class
     * @throws \Exception
     */
    public static function register($type, $key, $class = null)
    {
        if (is_array($key) && $class === null) {
            foreach ($key as $_key => $_class) {
                static::register($type, $_key, $_class);
            }

            return;
        }

        if (is_null($class)) {
            throw new \Exception('ClassRegistry::register Class string missing for key ' . $key);
        }

        if (!is_string($class)) {
            throw new \Exception('ClassRegistry::register Class string MUST be a string value ' . $key);
        }

        $className = App::className($class, $type);
        if (!$className) {
            Log::error("ClassRegistry::register() Class not found: " . $class . " of type " . $type);
            throw new ClassNotFoundException(sprintf("%s of type %s", $class, $type));
        }
        static::$_classes[$type][$key] = $className;
    }

    /**
     * Unregister class in namespace
     *
     * @param $type
     * @param $key
     * @throws \Exception
     */
    public static function unregister($type, $key)
    {
        if (!isset(static::$_classes[$type]) || !isset(static::$_classes[$type][$key])) {
            throw new \Exception('ClassRegistry::unregister Class namespace or key not found ' . $type . ':' . $key);
        }

        unset(static::$_classes[$type][$key]);
    }

    /**
     * Returns class name for namespace key
     *
     * @param $type
     * @param $key
     * @return null|string
     */
    public static function getClass($type, $key)
    {
        if (isset(static::$_classes[$type]) && isset(static::$_classes[$type][$key])) {
            return static::$_classes[$type][$key];
        }

        return null;
    }

    /**
     * Return a list of registered classes under a given namespace
     *
     * @param $type string
     * @return array Registered classes for namespace
     */
    public static function show($type)
    {
        if (isset(static::$_classes[$type])) {
            return static::$_classes[$type];
        }

        return [];
    }

    /**
     * Get class instance
     *
     * @param $type
     * @param $key
     * @return Object
     * @throws \Exception
     */
    public static function &get($type, $key)
    {
        if (!isset(static::$_classes[$type]) || !isset(static::$_classes[$type][$key])) {
            throw new ClassNotFoundException(sprintf('Class namespace or key not found for %s:%s', $type, $key));
        }

        if (!isset(static::$_instances[$type]) || !isset(static::$_instances[$type][$key])) {
            $class = static::$_classes[$type][$key];
            if (!class_exists($class)) {
                throw new ClassNotFoundException(sprintf('Class %s not found for %s:%s', $class, $type, $key));
            }

            static::$_instances[$type][$key] = new $class();
        }

        return static::$_instances[$type][$key];
    }

    /**
     * @param $type
     * @param $key
     * @param null $class
     * @throws \Exception
     * @deprecated Use register() instead
     */
    public static function add($type, $key, $class = null)
    {
        //trigger_error("ClassRegistry::" . __FUNCTION__ . " is deprecated. Use register() instead.");
        self::register($type, $key, $class);
    }

    /**
     * @param $type
     * @param $key
     * @return null
     * @throws \Exception
     * @deprecated Use get() instead
     */
    public static function createInstance($type, $key)
    {
        if (!isset(static::$_classes[$type]) || !isset(static::$_classes[$type][$key])) {
            throw new \RuntimeException(sprintf("ClassRegistry: Class not registered: %s:%s", $type, $key));
        }

        $factory = static::$_factories[$type] ?? function ($class) {
            return static::defaultFactory($class);
        };

        $class = static::$_classes[$type][$key];

        return $factory($class);
    }

    public static function defaultFactory($class)
    {

        if (!class_exists($class)) {
            throw new \RuntimeException("ClassRegistry: Class $class not found");
        }

        if (func_num_args() == 2) {
            $instance = new $class();
        } elseif (func_num_args() == 3) {
            $instance = new $class(func_get_arg(2));
        } else {
            throw new \RuntimeException("ClassRegistry: Class $class could not be constructed");
        }

        return $instance;
    }
}
