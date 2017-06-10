<?php
namespace Banana\Lib;

/**
 * Class SingletonTrait
 *
 * @package Banana\Lib
 */
class SingletonTrait
{
    /**
     * @var array
     */
    static protected $_instances = [];

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            self::$_instances[0] = new self();
        }

        return self::$_instances[0];
    }
}
