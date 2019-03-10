<?php
namespace Banana\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\Database\TypeInterface;
use InvalidArgumentException;
use PDO;

/**
 * Serialize type converter.
 *
 * Use to convert serialized data between PHP and the database types.
 */
class SerializeType extends Type implements TypeInterface
{

    /**
     * Convert a value data into a serialized string
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\Driver $driver The driver instance to convert with.
     * @return string|null
     */
    public function toDatabase($value, Driver $driver)
    {
        if (is_resource($value)) {
            throw new InvalidArgumentException('Cannot serialize a resource value');
        }

        return serialize($value);
    }

    /**
     * Convert string values to PHP arrays.
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\Driver $driver The driver instance to convert with.
     * @return string|null|array
     */
    public function toPHP($value, Driver $driver)
    {
        return unserialize($value);
    }

    /**
     * Get the correct PDO binding type for string data.
     *
     * @param mixed $value The value being bound.
     * @param \Cake\Database\Driver $driver The driver.
     * @return int
     */
    public function toStatement($value, Driver $driver)
    {
        return PDO::PARAM_STR;
    }

    /**
     * Marshalls request data into a serialize compatible structure.
     *
     * @param mixed $value The value to convert.
     * @return mixed Converted value.
     */
    public function marshal($value)
    {
        return $value;
    }
}
