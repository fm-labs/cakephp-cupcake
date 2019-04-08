<?php

namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Collection\Iterator\MapReduce;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetDecorator;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
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
        'implementedFinders' => [
            'withAttributes' => 'findWithAttributes',
            'byAttribute' => 'findByAttribute',
            'havingAttribute' => 'findHavingAttribute',
        ],
        'implementedMethods' => [
            'addAttribute' => 'addAttribute',
        ],
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
        $this->_table->hasMany('Attributes', [
            'className' => 'Banana.Attributes',
            'foreignKey' => 'foreign_key',
            'conditions' => ['Attributes.model' => $this->_table->registryAlias()]
        ]);
    }

    /**
     * @return \Cake\ORM\Table
     */
    public function attributesTable()
    {
        return $this->_table->Attributes;
    }

    /**
     * @param EntityInterface $entity The entity object
     * @param string $name Attribute name
     * @param mixed $value Attribute value
     * @return EntityInterface An Attribute entity
     */
    public function addAttribute(EntityInterface $entity, $name, $value = null)
    {
        return $this->attributesTable()->findOrCreate([
            'model' => $this->_table->registryAlias(),
            'foreign_key' => $entity->id,
            'name' => $name
        ], function($entity) use ($value) {
            $entity->value = $value;

            return $entity;
        });
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
            ->hydrate(false)
            ->all()
            ->toArray();

        $tableIds = Hash::extract($attrs, '{n}.foreign_key');
        if (empty($tableIds)) {
            return new ResultSetDecorator([]);
        }

        return $this->findWithAttributes($query->where([$this->_table->alias() . '.id IN' => $tableIds]));
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
            ->hydrate(false)
            ->all()
            ->toArray();

        $tableIds = Hash::extract($attrs, '{n}.foreign_key');
        if (empty($tableIds)) {
            return new ResultSetDecorator([]);
        }

        return $this->findWithAttributes($query->where([$this->_table->alias() . '.id IN' => $tableIds]));
    }

    /**
     * @param ResultSetDecorator $results The table results
     * @return ResultSetDecorator
     */
    public function _formatAttributesResult(ResultSetDecorator $results) {

        $results = $results->map(function(Entity $row) {
            if (isset($row->attributes)) {
                $attrs = collection($row->attributes);
                $row->attributes = $attrs->combine('name', 'value')->toArray();
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
        $mapper = function ($row, $key, MapReduce $mapReduce) {
            $mapReduce->emitIntermediate($row, $key);
        };

        $reducer = function ($bucket, $name, MapReduce $mapReduce) {
            $mapReduce->emit($bucket[0], $name);
        };

        $query->mapReduce($mapper, $reducer);
    }

    public function buildValidator(Event $event, Validator $validator, $name)
    {
    }

    public function buildRules(Event $event, RulesChecker $rules)
    {
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
    }
}
