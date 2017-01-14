<?php

namespace Banana\Lib;


use Cake\Core\App;

class ClassRegistry
{
    static $registry = [];
    
    public static function add($type, $key, $class = null)
    {
        if (is_array($key) && $class === null) {
            foreach ($key as $_key => $_class) {
                self::add($type, $_key, $_class);
            }
            return;
        }

        if (is_null($class)) {
            throw new \Exception('ClassRegistry: Class string missing for key ' . $key);
        }

        if (!is_string($class)) {
            throw new \Exception('ClassRegistry: Class string MUST be a string value ' . $key);
        }

        self::$registry[$type][$key] = $class;
    }
    
    public static function createInstance($type, $key) 
    {
        if (isset(static::$registry[$type]) && isset(static::$registry[$type][$key])) {
            $class = static::$registry[$type][$key];

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