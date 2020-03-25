<?php
declare(strict_types=1);

namespace Banana\Model;

use Cake\Datasource\EntityInterface;

/**
 * Class ArrayTableEntity
 *
 * @package Banana\Model
 */
class ArrayTableEntity extends \ArrayObject implements EntityInterface
{
    /**
     * Magic getter to access properties that have been set in this entity
     *
     * @param string $property Name of the property to access
     * @return mixed
     */
    public function &__get($property)
    {
        return $this->get($property);
    }

    /**
     * Magic setter to add or edit a property in this entity
     *
     * @param string $property The name of the property to set
     * @param mixed $value The value to set to the property
     * @return void
     */
    public function __set($property, $value)
    {
        $this->set($property, $value);
    }

    /**
     * Returns whether this entity contains a property named $property
     * regardless of if it is empty.
     *
     * @param string $property The property to check.
     * @return bool
     * @see \Cake\ORM\Entity::has()
     */
    public function __isset($property)
    {
        return $this->has($property);
    }

    /**
     * Removes a property from this entity
     *
     * @param string $property The property to unset
     * @return void
     */
    public function __unset($property)
    {
        $this->unsetProperty($property);
    }

    /**
     * Sets one or multiple properties to the specified value
     *
     * @param string|array $property the name of property to set or a list of
     * properties with their respective values
     * @param mixed $value The value to set to the property or an array if the
     * first argument is also an array, in which case will be treated as $options
     * @param array $options options to be used for setting the property. Allowed option
     * keys are `setter` and `guard`
     * @return \Cake\Datasource\EntityInterface
     */
    public function set($property, $value = null, array $options = [])
    {
        if (is_array($property)) {
            foreach ($property as $_property => $_value) {
                $this[$_property] = $_value;
            }
        }
        $this[$property] = $value;

        return $this;
    }

    /**
     * Returns the value of a property by name
     *
     * @param string $property the name of the property to retrieve
     * @return mixed
     */
    public function &get($property)
    {
        $val = null;
        if ($this->offsetExists($property)) {
            $val = $this->offsetGet($property);
        }

        return $val;
    }

    /**
     * Returns whether this entity contains a property named $property
     * regardless of if it is empty.
     *
     * @param string $property The property to check.
     * @return bool
     */
    public function has($property)
    {
        return isset($this[$property]);
    }

    /**
     * Removes a property or list of properties from this entity
     *
     * @param string|array $property The property to unset.
     * @return \Cake\ORM\
     */
    public function unsetProperty($property)
    {
        if (isset($this[$property])) {
            unset($this[$property]);
        }

        return $this;
    }

    /**
     * Get/Set the hidden properties on this entity.
     *
     * If the properties argument is null, the currently hidden properties
     * will be returned. Otherwise the hidden properties will be set.
     *
     * @param null|array $properties Either an array of properties to hide or null to get properties
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function hiddenProperties($properties = null)
    {
        if ($properties === null) {
            return [];
        }

        return $this;
    }

    /**
     * Get/Set the virtual properties on this entity.
     *
     * If the properties argument is null, the currently virtual properties
     * will be returned. Otherwise the virtual properties will be set.
     *
     * @param null|array $properties Either an array of properties to treat as virtual or null to get properties
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function virtualProperties($properties = null)
    {
        if ($properties === null) {
            return [];
        }

        return $this;
    }

    /**
     * Get the list of visible properties.
     *
     * @return array A list of properties that are 'visible' in all representations.
     */
    public function visibleProperties()
    {
        return array_keys((array)$this);
    }

    /**
     * Returns an array with all the visible properties set in this entity.
     *
     * *Note* hidden properties are not visible, and will not be output
     * by toArray().
     *
     * @return array
     */
    public function toArray()
    {
        return (array)$this;
    }

    /**
     * Returns an array with the requested properties
     * stored in this entity, indexed by property name
     *
     * @param array $properties list of properties to be returned
     * @param bool $onlyDirty Return the requested property only if it is dirty
     * @return array
     */
    public function extract(array $properties, $onlyDirty = false)
    {
        return $this->toArray();
    }

    /**
     * Sets the dirty status of a single property. If called with no second
     * argument, it will return whether the property was modified or not
     * after the object creation.
     *
     * When called with no arguments it will return whether or not there are any
     * dirty property in the entity
     *
     * @param string|null $property the field to set or check status for
     * @param null|bool $isDirty true means the property was changed, false means
     * it was not changed and null will make the function return current state
     * for that property
     * @return bool whether the property was changed or not
     */
    public function dirty($property = null, $isDirty = null)
    {
        return false;
    }

    /**
     * Sets the entire entity as clean, which means that it will appear as
     * no properties being modified or added at all. This is an useful call
     * for an initial object hydration
     *
     * @return void
     */
    public function clean()
    {
    }

    /**
     * Returns whether or not this entity has already been persisted.
     * This method can return null in the case there is no prior information on
     * the status of this entity.
     *
     * If called with a boolean, this method will set the status of this instance.
     * Using `true` means that the instance has not been persisted in the database, `false`
     * that it already is.
     *
     * @param bool|null $new Indicate whether or not this instance has been persisted.
     * @return bool If it is known whether the entity was already persisted
     * null otherwise
     */
    public function isNew($new = null)
    {
        return false;
    }

    /**
     * Sets the error messages for a field or a list of fields. When called
     * without the second argument it returns the validation
     * errors for the specified fields. If called with no arguments it returns
     * all the validation error messages stored in this entity.
     *
     * When used as a setter, this method will return this entity instance for method
     * chaining.
     *
     * @param string|array|null $field The field to get errors for.
     * @param string|array|null $errors The errors to be set for $field
     * @param bool $overwrite Whether or not to overwrite pre-existing errors for $field
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function errors($field = null, $errors = null, $overwrite = false)
    {
        if ($errors === null) {
            return [];
        }

        return $this;
    }

    /**
     * Stores whether or not a property value can be changed or set in this entity.
     * The special property `*` can also be marked as accessible or protected, meaning
     * that any other property specified before will take its value. For example
     * `$entity->accessible('*', true)`  means that any property not specified already
     * will be accessible by default.
     *
     * @param string|array $property Either a single or list of properties to change its accessibility.
     * @param bool|null $set true marks the property as accessible, false will
     * mark it as protected.
     * @return \Cake\Datasource\EntityInterface|bool
     */
    public function accessible($property, $set = null)
    {
        return true;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return json_encode($this->toArray());
    }
}
