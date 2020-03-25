<?php
namespace Banana\Database\Type;

use Cake\Database\Driver;
use Cake\Database\TypeFactory;
use PDO;

/**
 * Class JsonType - DEPRECATED
 *
 * JSON database type for the cake's ORM
 *
 * ! DEPRECATION NOTICE !
 * As of CakePHP v3.3.0 the JsonType is part of the official package.
 *
 * @package Banana\Database\Type
 * @deprecated
 */
class JsonType extends \Cake\Database\TypeFactory
{

    /**
     * @param mixed $value
     * @param Driver $driver
     * @return mixed|null
     */
    public function toPHP($value, Driver $driver)
    {
        if ($value === null) {
            return null;
        }

        return json_decode($value, true);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function marshal($value)
    {
        if (is_array($value) || $value === null) {
            return $value;
        }

        return json_decode($value, true);
    }

    /**
     * @param $value
     * @param Driver $driver
     * @return string
     */
    public function toDatabase($value, Driver $driver)
    {
        return json_encode($value);
    }

    /**
     * @param $value
     * @param Driver $driver
     * @return int
     */
    public function toStatement($value, Driver $driver)
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }
}
