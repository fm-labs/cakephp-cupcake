<?php
declare(strict_types=1);

namespace Banana\Model;

use Cake\Utility\Inflector;

/**
 * Class TableInputSchema
 *
 * @package Banana\Model
 */
class TableInputSchema
{
    /**
     * @var array List of configured fields
     */
    protected $_fields = [];

    /**
     * @param $fieldName
     * @param array $config
     * @return $this
     */
    public function addField($fieldName, array $config = [])
    {
        $config = array_merge([
            'type' => 'text',
            'label' => Inflector::humanize($fieldName),
            'help' => null,
            'required' => null,
            'searchable' => false,
            'sortable' => false,
        ], $config);
        $this->_fields[$fieldName] = $config;

        return $this;
    }

    /**
     * @param $fieldName
     * @return array|null
     */
    public function field($fieldName)
    {
        return $this->_fields[$fieldName] ?? null;
    }

    /**
     * @return array
     */
    public function fields()
    {
        return $this->_fields;
    }
}
