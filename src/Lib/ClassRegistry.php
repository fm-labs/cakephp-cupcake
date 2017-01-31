<?php

namespace Banana\Lib;
use Banana\Exception\ClassNotFoundException;

/**
 * Class ClassRegistry
 *
 * Register class-alias for class locations in namespaces
 *
 * @package Banana\Lib
 */
class ClassRegistry
{
    static protected $_classes = [];
    static protected $_instances = [];

    /**
     * Register class in namespace
     *
     * @param $ns
     * @param $key
     * @param null $class
     * @throws \Exception
     */
    static public function register($ns, $key, $class = null)
    {
        if (is_array($key) && $class === null) {
            foreach ($key as $_key => $_class) {
                static::register($ns, $_key, $_class);
            }
            return;
        }

        if (is_null($class)) {
            throw new \Exception('ClassRegistry::register Class string missing for key ' . $key);
        }

        if (!is_string($class)) {
            throw new \Exception('ClassRegistry::register Class string MUST be a string value ' . $key);
        }

        static::$_classes[$ns][$key] = $class;
    }

    /**
     * Unregister class in namespace
     *
     * @param $ns
     * @param $key
     * @throws \Exception
     */
    static public function unregister($ns, $key)
    {
        if (!isset(static::$_classes[$ns]) || !isset(static::$_classes[$ns][$key])) {
            throw new \Exception('ClassRegistry::unregister Class namespace or key not found ' . $ns . ':' . $key);
        }

        unset(static::$_classes[$ns][$key]);
    }

    /**
     * Returns class name for namespace key
     *
     * @param $ns
     * @param $key
     * @return null|string
     */
    static public function getClass($ns, $key)
    {
        if (isset(static::$_classes[$ns]) && isset(static::$_classes[$ns][$key])) {
            return static::$_classes[$ns][$key];
        }
        return null;
    }

    /**
     * Return a list of registered classes under a given namespace
     *
     * @param $ns string
     * @return array Registered classes for namespace
     */
    static public function show($ns)
    {
        if (isset(static::$_classes[$ns])) {
            return static::$_classes[$ns];
        }
        return [];
    }

    /**
     * Get class instance
     *
     * @param $ns
     * @param $key
     * @return Object
     * @throws \Exception
     */
    static public function &get($ns, $key)
    {
        if (!isset(static::$_classes[$ns]) || !isset(static::$_classes[$ns][$key])) {
            throw new ClassNotFoundException(sprintf('Class namespace or key not found for %s:%s', $ns, $key));
        }

        if (!isset(static::$_instances[$ns]) || !isset(static::$_instances[$ns][$key])) {
            $class = static::$_classes[$ns][$key];
            if (!class_exists($class)) {
                throw new ClassNotFoundException(sprintf('Class %s not found for %s:%s', $class, $ns, $key));
            }

            static::$_instances[$ns][$key] = new $class();
        }
        return static::$_instances[$ns][$key];
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
        //trigger_error("ClassRegistry::" . __FUNCTION__ . " is deprecated. Use get() instead.");
        if (isset(static::$_classes[$type]) && isset(static::$_classes[$type][$key])) {
            $class = static::$_classes[$type][$key];

            if (!class_exists($class)) {
                throw new \Exception('ClassRegistry: Class $class not found');
            }

            if (func_num_args() == 2) {
                $instance = new $class();
            } elseif (func_num_args() == 3) {
                $instance = new $class(func_get_arg(2));
            }

            return $instance;
        }

        return null;
    }
}