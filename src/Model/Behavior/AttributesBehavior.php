<?php

namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Collection\Collection;
use Cake\Collection\Iterator\MapReduce;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetDecorator;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Class AttributesBehavior
 *
 * @package Eav\Model\Behavior
 */
class AttributesBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        //'attributesTableClass' => 'Attributes',
        'attributesTableName' => 'attributes',
        'attributesPropertyName' => 'attrs',
        'attributes' => [
        //    'foo' => ['type' => 'string', 'required' => true, 'default' => null]
        ],

        'implementedFinders' => [
            'withAttributes' => 'findWithAttributes',
            'byAttribute' => 'findByAttribute',
            'havingAttribute' => 'findHavingAttribute',
        ],
        'implementedMethods' => [
            'createAttribute' => 'createAttribute',
            'saveAttribute' => 'saveAttribute',
            'isAttribute' => 'isAttribute',
            'getAttributesTable' => 'attributesTable',
            'getAttributesSchema' => 'attributesSchema'
        ],
    ];

    /**
     * @var array Attributes schema description
     */
    protected $_schema;

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
        $targetAlias = /*$this->_table->getAlias() .*/ 'Attributes';
        $targetTable = TableRegistry::getTableLocator()->get($targetAlias, [
            //'table' => $this->_config['attributesTableName']
        ]);
        $targetTable->setTable($this->_config['attributesTableName']);

        $this->_table->hasMany($targetAlias, [
            //'className' => $this->_config['attributesTableClass'],
            'foreignKey' => 'foreign_key',
            'conditions' => ['Attributes.model' => $this->_table->getRegistryAlias()],
            'dependent' => true,
            'propertyName' => 'attributes',
            'targetTable' => $targetTable
        ]);
    }

    /**
     * @return \Cake\ORM\Table
     */
    public function attributesTable()
    {
        return $this->_table->getAssociation('Attributes');
    }

    public function attributesSchema()
    {
        if (!isset($this->_schema)) {
            $attributes = (array)$this->getConfig('attributes');
            if (method_exists($this->_table, 'buildAttributes')) {
                $attributes = call_user_func([$this->_table, 'buildAttributes'], $attributes);
            }
            //$attributes = $this->_table->attributesSchema ?? [];
            //$attributes = $this->_table instanceof AttributesAwareTrait ? $this->_table->getAttributes() : [];

            //$event = $this->_table->dispatchEvent('Model.buildAttributes', compact('attributes'));
            //$attributes = $event->getData('attributes');

            $this->_schema = $attributes;
        }

        return $this->_schema;
    }

    /**
     * @param EntityInterface $entity The entity object
     * @param string $name Attribute name
     * @param mixed $value Attribute value
     * @return EntityInterface An Attribute entity
     */
    public function createAttribute(EntityInterface $entity, $name, $value = null)
    {
        return $this->attributesTable()->findOrCreate([
            'model' => $this->_table->getRegistryAlias(),
            'foreign_key' => $entity->id,
            'name' => $name,
        ], function ($entity) use ($value) {
            $entity->value = $value;

            return $entity;
        });
    }

    public function saveAttribute(EntityInterface $attr)
    {
        return $this->attributesTable()->save($attr);
    }

    /**
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findWithAttributes(Query $query, array $options = [])
    {
        $query->contain(['Attributes']);
        $query->formatResults([$this, '_formatAttributesResult']);

        return $query;
    }

    /**
     * Find rows by given attributes (key-value-pairs)
     *
     * @param Query $query
     * @param array $options Attributes key-value-pairs
     * @return Query
     */
    public function findByAttribute(Query $query, array $options = [])
    {
        if (empty($options)) {
            throw new \InvalidArgumentException("Attribute key-value pair(s) missing");
        }

        $attrsQuery = $this->attributesTable()->find();
        foreach ($options as $k => $v) {
            $attrsQuery->where(['Attributes.name' => $k, 'Attributes.value' => $v]);
        }
        $attrs = $attrsQuery
            ->enableHydration(false)
            ->all()
            ->toArray();

        $tableIds = Hash::extract($attrs, '{n}.foreign_key');
        if (empty($tableIds)) {
            return new ResultSetDecorator([]);
        }

        return $this->findWithAttributes($query->where([$this->_table->getAlias() . '.id IN' => $tableIds]));
    }

    /**
     * Find rows by given attributes (key-value-pairs)
     *
     * @param Query $query
     * @param array $options Attributes key-value-pairs
     * @return Query
     */
    public function findHavingAttribute(Query $query, array $options = [])
    {
        if (empty($options)) {
            throw new \InvalidArgumentException("Attribute key-value pair(s) missing");
        }

        $attrsQuery = $this->attributesTable()->find();
        foreach ($options as $k) {
            $attrsQuery->where(['Attributes.name' => $k]);
        }
        $attrs = $attrsQuery
            ->enableHydration(false)
            ->all()
            ->toArray();

        $tableIds = Hash::extract($attrs, '{n}.foreign_key');
        if (empty($tableIds)) {
            return new ResultSetDecorator([]);
        }

        return $this->findWithAttributes($query->where([$this->_table->getAlias() . '.id IN' => $tableIds]));
    }

    /**
     * @param ResultSetDecorator $results The table results
     * @return ResultSetDecorator
     */
    public function _formatAttributesResult(Collection $results)
    {
        $results = $results->map(function (Entity $row) {
            if (isset($row->attributes)) {
                $col = collection($row->attributes);
                $attrs = $col->combine('name', 'value')->toArray();

                foreach ($attrs as $key => $value) {
                    $row->{$key} = $row->{$key} ?: $value;
                }

                $row->{$this->_config['attributesPropertyName']} = $attrs;
                $row->clean();
            }

            return $row;
        });

        return $results;
    }

    /**
     * 'beforeFind' callback
     *
     * Applies a MapReduce to the query, which resolves entity attributes
     * after the find operation.
     *
     * @param Event $event
     * @param Query $query
     * @param array $options
     * @param $primary
     */
    public function beforeFind(Event $event, Query $query, $options, $primary)
    {
        /*
        $mapper = function ($row, $key, MapReduce $mapReduce) {
            $mapReduce->emitIntermediate($row, $key);
        };

        $reducer = function ($bucket, $name, MapReduce $mapReduce) {
            $mapReduce->emit($bucket[0], $name);
        };

        $query->mapReduce($mapper, $reducer);
        */

        if (isset($options['attributes']) && $options['attributes'] === true) {
            $query->find('withAttributes');
        }
    }

    public function buildValidator(Event $event, Validator $validator, $name)
    {
        foreach ($this->attributesSchema() as $field => $config) {
            $required = $config['required'] ?? false;
            $validator->requirePresence($field, $required);
            $validator->allowEmptyString($field);

            $fieldValidator = $config['validator'] ?? null;
            if (is_callable($fieldValidator)) {
                //$fieldValidator($validator, $name);
                call_user_func($fieldValidator, $validator, $name);
            }
        }
    }

    /*
    public function buildRules(Event $event, RulesChecker $rules)
    {
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
    }
    */

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        //debug("afterSave");
        //debug($entity->attributes);
        //debug($entity->attributes_data);
        if ($entity->isDirty($this->_config['attributesPropertyName'])) {
            //debug("attributes are dirty");
            foreach($entity->get($this->_config['attributesPropertyName']) as $key => $val) {
                if ($this->isAttribute($key)) {
                    $attr = $this->createAttribute($entity, $key);
                    $attr->value = $val;
                    if (!$this->saveAttribute($attr)) {
                        debug("failed to save attr $key");
                        $attr->setError('attributes_data', 'Error saving attr ' . $key);
                        return false;
                    }
                }
            }
        }

        /** @var Entity $entity */
        foreach ($entity->getDirty() as $key) {
            if ($this->isAttribute($key)) {
                $attr = $this->createAttribute($entity, $key);
                $attr->value = $entity->get($key);
                if (!$this->saveAttribute($attr)) {
                    debug("failed to save attr $key");
                    $attr->setError('attributes', 'Error saving attr ' . $key);
                    return false;
                }
            }
        }

        return true;
    }

    public function isAttribute($name)
    {
        if ($name == $this->_config['attributesPropertyName']) {
            return false;
        }

        if ($this->_table->getSchema()->getColumn($name)) {
            return false;
        }

        if (!isset($this->attributesSchema()[$name])) {
            return false;
        }

        return true;
    }
}
