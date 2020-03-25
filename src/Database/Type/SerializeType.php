<?php
namespace Banana\Database\Type;

use Cake\Database\Driver;
use Cake\Database\DriverInterface;
use Cake\Database\TypeFactory;
use Cake\Database\TypeInterface;
use InvalidArgumentException;
use PDO;

/**
 * Serialize type converter.
 *
 * Use to convert serialized data between PHP and the database types.
 */
class SerializeType extends \Cake\Database\TypeFactory implements TypeInterface
{

    /**
     * Convert a value data into a serialized string
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\Driver $driver The driver instance to convert with.
     * @return string|null
     */
    public function toDatabase($value, DriverInterface $driver)
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
    public function toPHP($value, DriverInterface $driver)
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
    public function toStatement($value, DriverInterface $driver)
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

    }

    /**
     * Returns type identifier name for this object.
     *
     * @return string|null The type identifier name for this object.
     */
    public function getName(): ?string
    {

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

    }


}
