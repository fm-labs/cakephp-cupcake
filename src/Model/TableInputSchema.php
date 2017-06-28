<?php

namespace Banana\Model;

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
        $config = array_merge(['type' => 'text'], $config);
        $this->_fields[$fieldName] = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function fields()
    {
        return $this->_fields;
    }
}
