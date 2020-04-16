<?php
declare(strict_types=1);

namespace Banana\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\TypeFactory;
use Cake\Database\TypeInterface;
use Cake\Utility\Text;
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
class JsonType extends TypeFactory implements TypeInterface
{
    /**
     * @param mixed $value
     * @param \Cake\Database\DriverInterface $driver
     * @return mixed|null
     */
    public function toPHP($value, DriverInterface $driver)
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
     * @param \Cake\Database\DriverInterface $driver
     * @return string
     */
    public function toDatabase($value, DriverInterface $driver)
    {
        return json_encode($value);
    }

    /**
     * @param $value
     * @param \Cake\Database\DriverInterface $driver
     * @return int
     */
    public function toStatement($value, DriverInterface $driver)
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }

    /**
     * Returns the base type name that this class is inheriting.
     *
     * This is useful when extending base type for adding extra functionality,
     * but still want the rest of the framework to use the same assumptions it would
     * do about the base type it inherits from.
     *
     * @return string|null The base type name that this class is inheriting.
     */
    public function getBaseType(): ?string
    {
        return null;
    }

    /**
     * Returns type identifier name for this object.
     *
     * @return string|null The type identifier name for this object.
     */
    public function getName(): ?string
    {
        return null;
    }

    /**
     * Generate a new primary key value for a given type.
     *
     * This method can be used by types to create new primary key values
     * when entities are inserted.
     *
     * @return mixed A new primary key value.
     * @see \Cake\Database\Type\UuidType
     */
    public function newId()
    {
        return Text::uuid();
    }
}
