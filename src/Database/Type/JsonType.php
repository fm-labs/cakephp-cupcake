<?php
declare(strict_types=1);

namespace Cupcake\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use Cake\Database\Type\BatchCastingInterface;
use InvalidArgumentException;
use PDO;

/**
 * Class JsonType
 *
 * JSON database type for the cake's ORM.
 *
 * @package Cupcake\Database\Type
 * @deprecated As of CakePHP v3.3.0 the JsonType is part of the official package.
 */
class JsonType extends BaseType implements BatchCastingInterface
{
    /**
     * @inheritDoc
     */
    public function toPHP($value, Driver $driver): mixed
    {
        if ($value === null) {
            return null;
        }

        return json_decode($value, true);
    }

    /**
     * @inheritDoc
     */
    public function marshal($value): mixed
    {
        if (is_string($value) && preg_match('/^\{([\w\W\n]*)\}$/m', $value)) {
            return json_decode($value, true);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function toDatabase($value, Driver $driver): mixed
    {
        if ($value === null) {
            return null;
        }

        $val = json_encode($value);
        if (json_last_error() > 0) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        return $val;
    }

    /**
     * @inheritDoc
     */
    public function toStatement($value, Driver $driver): int
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }

    /**
     * Returns an array of the values converted to the PHP representation of
     * this type.
     *
     * @param array $values The original array of values containing the fields to be casted
     * @param array<string> $fields The field keys to cast
     * @param \Cake\Database\Driver $driver Object from which database preferences and configuration will be extracted.
     * @return array
     */
    public function manyToPHP(array $values, array $fields, Driver $driver): array
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
