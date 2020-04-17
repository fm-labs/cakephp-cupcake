<?php
declare(strict_types=1);

namespace Banana\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\Type\BaseType;
use Cake\Database\Type\BatchCastingInterface;
use InvalidArgumentException;
use PDO;

/**
 * Serialize type converter.
 *
 * Use to convert serialized data between PHP and the database types.
 */
class SerializeType extends BaseType implements BatchCastingInterface
{
    /**
     * Convert a value data into a serialized string
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\DriverInterface $driver The driver instance to convert with.
     * @return string|null
     */
    public function toDatabase($value, DriverInterface $driver)
    {
        if (is_resource($value)) {
            throw new InvalidArgumentException('Cannot serialize a resource value');
        }

        if ($value === null) {
            return $value;
        }

        return serialize($value);
    }

    /**
     * Convert string values to PHP arrays.
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\DriverInterface $driver The driver instance to convert with.
     * @return string|null|array
     */
    public function toPHP($value, DriverInterface $driver)
    {
        if (is_string($value)) {
            return unserialize($value);
        }

        return $value;
    }

    /**
     * Get the correct PDO binding type for string data.
     *
     * @param mixed $value The value being bound.
     * @param \Cake\Database\DriverInterface $driver The driver.
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
     * Marshalls request data into a serialize compatible structure.
     *
     * @param mixed $value The value to convert.
     * @return mixed Converted value.
     */
    public function marshal($value)
    {
        return $value;
    }

    /**
     * Returns an array of the values converted to the PHP representation of
     * this type.
     *
     * @param array $values The original array of values containing the fields to be casted
     * @param string[] $fields The field keys to cast
     * @param \Cake\Database\DriverInterface $driver Object from which database preferences and configuration will be extracted.
     * @return array
     */
    public function manyToPHP(array $values, array $fields, DriverInterface $driver): array
    {
        foreach ($fields as $field) {
            if (!isset($values[$field])) {
                continue;
            }

            $values[$field] = $this->toPHP($values[$field], $driver);
        }

        return $values;
    }
}
