<?php
declare(strict_types=1);

namespace Banana\View;

use Cake\Form\Schema;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Class ViewModuleSchema
 *
 * @package Banana\View
 */
class ViewModuleSchema extends Schema
{
    /**
     * The fields in this schema.
     *
     * @var array
     */
    protected $_fields = [];

    /**
     * @var array
     */
    protected $_options = [];

    /**
     * The default values for fields.
     *
     * @var array
     */
    protected $_fieldDefaults = [
        'type' => null,
        'length' => null,
        'precision' => null,
    ];

    /**
     * @var array
     */
    protected $_optionDefaults = [
        'select' => [
            'model' => null,
            'source' => null,
        ],
    ];

    /**
     * Add multiple fields to the schema.
     *
     * @param array $fields The fields to add.
     * @return $this
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $name => $attrs) {
            $this->addField($name, $attrs);
        }

        return $this;
    }

    /**
     * Adds a field to the schema.
     *
     * @param string $name The field name.
     * @param string|array $attrs The attributes for the field, or the type
     *   as a string.
     * @param array $options The input attributes fo the field
     * @return $this
     */
    public function addField($name, $attrs, array $options = [])
    {
        if (is_string($attrs)) {
            $attrs = ['type' => $attrs];
        }
        $whitelistedAttrs = array_intersect_key($attrs, $this->_fieldDefaults);

        $extraAttrs = $extraDefaults = [];
        /*
        if (isset($this->_extraFieldDefaults[$attrs['type']])) {
            $extraAttrs = array_intersect_key($attrs, $this->_extraFieldDefaults[$attrs['type']]);
            $extraDefaults = $this->_extraFieldDefaults[$attrs['type']];
        }
        */
        $this->_fields[$name] = $whitelistedAttrs + $extraAttrs + $this->_fieldDefaults + $extraDefaults;
        $this->_options[$name] = $options;

        return $this;
    }

    /**
     * @param $field
     * @return array
     */
    public function options($field)
    {
        if (!isset($this->_options[$field])) {
            return [];
        }

        $options = $this->_options[$field];

        if (array_key_exists('model', $options)) {
            try {
                //@ todo Implement custom finder for view modules
                $options['options'] = TableRegistry::getTableLocator()->get($options['model'])->find('list')->toArray();
            } catch (\Exception $ex) {
                Log::error(sprintf('ViewModuleSchema: Unable to read select option list from model %s', $options['model']));
            }
            unset($options['model']);
        }

        if (array_key_exists('source', $options)) {
            if (is_callable($options['source'])) {
                $options['options'] = call_user_func($options['source']);
            }
            unset($options['source']);
        }

        return $options;
    }

    /**
     * Removes a field to the schema.
     *
     * @param string $name The field to remove.
     * @return $this
     */
    public function removeField($name)
    {
        unset($this->_fields[$name]);

        return $this;
    }

    /**
     * Get the list of fields in the schema.
     *
     * @return array The list of field names.
     */
    public function fields()
    {
        return array_keys($this->_fields);
    }

    /**
     * Get the attributes for a given field.
     *
     * @param string $name The field name.
     * @return null|array The attributes for a field, or null.
     */
    public function field($name)
    {
        if (!isset($this->_fields[$name])) {
            return null;
        }

        return $this->_fields[$name];
    }

    /**
     * Get the type of the named field.
     *
     * @param string $name The name of the field.
     * @return string|null Either the field type or null if the
     *   field does not exist.
     */
    public function fieldType($name)
    {
        $field = $this->field($name);
        if (!$field) {
            return null;
        }

        return $field['type'];
    }

    /**
     * Get the printable version of this object
     *
     * @return array
     */
    public function __debugInfo()
    {
        return [
            '_fields' => $this->_fields,
        ];
    }
}
