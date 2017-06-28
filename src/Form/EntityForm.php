<?php

namespace Banana\Form;

use Banana\Model\TableInputSchema;
use Cake\Datasource\EntityInterface;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * Class EntityForm
 *
 * @package Banana\Form
 */
class EntityForm extends Form
{
    /**
     * @var array
     */
    protected $_controls = [];

    /**
     * @var EntityInterface
     */
    protected $_entity;

    /**
     * @param EntityInterface $entity
     */
    public function __construct(EntityInterface $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * @return EntityInterface
     */
    public function entity()
    {
        return $this->_entity;
    }

    /**
     * @param array $inputs
     * @return array
     * @deprecated Use controls() instead
     */
    public function inputs($inputs = [])
    {
        return $this->controls($inputs);
    }

    /**
     * @param array $controls
     * @return array
     */
    public function controls($controls = [])
    {
        if (!empty($controls)) {
            return $this->_controls = $controls;
        }

        if (empty($this->_controls)) {
            //$this->_controls = $this->manager()->buildFormInputs();
        }

        return $this->_controls;
    }

    /**
     * @param TableInputSchema $inputs
     * @return TableInputSchema
     */
    protected function _buildControls(TableInputSchema $inputs)
    {
        return $inputs;
    }

    /**
     * @param \Cake\Form\Schema $schema The schema to customize.
     * @return \Cake\Form\Schema The schema to use.
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema;
    }


    /**
     * @param \Cake\Validation\Validator $validator The validator to customize.
     * @return \Cake\Validation\Validator The validator to use.
     */
    protected function _buildValidator(Validator $validator)
    {
        return $validator;
    }
}
