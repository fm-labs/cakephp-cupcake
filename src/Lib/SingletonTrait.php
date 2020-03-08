<?php
namespace Banana\Lib;

/**
 * Class SingletonTrait
 *
 * @package Banana\Lib
 */
trait SingletonTrait
{
    /**
     * @var array
     */
    protected static $_instances = [];

    /**
     * @return object
     */
    public static function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            self::$_instances[0] = new self();
        }

        return self::$_instances[0];
    }
}
