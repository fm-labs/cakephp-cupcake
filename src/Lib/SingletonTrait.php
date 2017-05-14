<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/15/17
 * Time: 12:23 AM
 */

namespace Banana\Lib;


class SingletonTrait
{

    static protected $_instances = [];

    /**
     * @return self
     */
    static public function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            self::$_instances[0] = new self();
        }
        return self::$_instances[0];
    }
}