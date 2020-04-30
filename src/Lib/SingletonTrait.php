<?php
declare(strict_types=1);

namespace Cupcake\Lib;

/**
 * Class SingletonTrait
 *
 * @package Cupcake\Lib
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
