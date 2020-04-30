<?php
declare(strict_types=1);

namespace Cupcake\Model\Behavior;

use ArrayObject;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
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
            'getAttributesSchema' => 'attributesSchema',
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
    public function initialize(array $config): void
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
            'targetTable' => $targetTable,
        ]);
    }

    /**
     * @return \Cake\ORM\Association
     */
    public function attributesTable()
    {
        return $this->_table->getAssociation('Attributes');
    }

    /**
     * @return array
     */
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
     * @param \Cake\Datasource\EntityInterface $entity The entity object
     * @param string $name Attribute name
     * @param mixed $value Attribute value
     * @return \Cake\Datasource\EntityInterface An Attribute entity
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

    /**
     * @param \Cake\Datasource\EntityInterface $attr Attribute Entity object
     * @return bool|\Cake\Datasource\EntityInterface
     */
    public function saveAttribute(EntityInterface $attr)
    {
        return $this->attributesTable()->save($attr);
    }

    /**
     * @param \Cake\ORM\Query $query Query object
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findWithAttributes(Query $query, array $options = [])
    {
        $query->contain(['Attributes']);
        $query->formatResults([$this, 'formatAttributesResult']);

        return $query;
    }

    /**
     * Find rows by given attributes (key-value-pairs)
     *
     * @param \Cake\ORM\Query $query Query object
     * @param array $options Attributes key-value-pairs
     * @return \Cake\ORM\Query
     */
    public function findByAttribute(Query $query, array $options = [])
    {
        if (empty($options)) {
            throw new \InvalidArgumentException("Attribute key-value pair(s) missing");
        }

        $attrsQuery = $this->attributesTable()->find();
        foreach ($options as $k => $v) {
            $cond = ['Attributes.name' => $k, 'Attributes.value' => $v];
            if ($v === null) {
                unset($cond['Attributes.value']);
                $cond['Attributes.value IS'] = $v;
            }
            $attrsQuery->where($cond);
        }
        $attrs = $attrsQuery
            ->enableHydration(false)
            ->all()
            ->toArray();

        $tableIds = Hash::extract($attrs, '{n}.foreign_key');
        if (empty($tableIds)) {
            $tableIds = [0];
        }
        $query->where([$this->_table->getAlias() . '.id IN' => $tableIds]);

        return $this->findWithAttributes($query);
    }

    /**
     * Find rows by given attributes (key-value-pairs)
     *
     * @param \Cake\ORM\Query $query The Query object
     * @param array $options List of attribute names
     * @return \Cake\ORM\Query
     * @TODO Support for multiple attributes
     */
    public function findHavingAttribute(Query $query, array $options = [])
    {
        if (empty($options)) {
            throw new \InvalidArgumentException("Attribute key-value pair(s) missing");
        }

        $attrsQuery = $this->attributesTable()
            ->find()
            ->where(function (QueryExpression $exp, Query $query) use ($options) {
                return $exp->in('name', $options);
            });

        $attrs = $attrsQuery
            ->enableHydration(false)
            ->all()
            ->toArray();

        $tableIds = Hash::extract($attrs, '{n}.foreign_key');
        if (empty($tableIds)) {
            $tableIds = [0];
        }
        $query->where([$this->_table->getAlias() . '.id IN' => $tableIds]);

        return $this->findWithAttributes($query);
    }

    /**
     * @param \Cake\Collection\CollectionInterface $results The table results
     * @return \Cake\Collection\CollectionInterface|\Cake\Datasource\ResultSetInterface
     */
    public function formatAttributesResult(\Cake\Collection\CollectionInterface $results)
    {
        $results = $results->map(function (Entity $row) {
            if ($row->get('attributes')) {
                $col = collection($row->get('attributes'));
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
     * @param \Cake\Event\EventInterface $event Event object
     * @param \Cake\ORM\Query $query Query object
     * @param array $options Options
     * @param bool $primary Primary flag
     * @return void
     */
    public function beforeFind(EventInterface $event, Query $query, $options, $primary): void
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

    /**
     * @param \Cake\Event\EventInterface $event Event object
     * @param \Cake\Validation\Validator $validator Validator object
     * @param string $name Validator name
     * @return void
     */
    public function buildValidator(EventInterface $event, Validator $validator, string $name): void
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

    /**
     * @param \Cake\Event\EventInterface $event Event object
     * @param \Cake\Datasource\EntityInterface|\Cake\ORM\Entity $entity Entity object
     * @param \ArrayObject $options Options
     * @return bool
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): bool
    {
        //debug("afterSave");
        //debug($entity->attributes);
        //debug($entity->attributes_data);
        if ($entity->isDirty($this->_config['attributesPropertyName'])) {
            //debug("attributes are dirty");
            foreach ($entity->get($this->_config['attributesPropertyName']) as $key => $val) {
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

    /**
     * @param string $name Attribute name
     * @return bool
     */
    public function isAttribute(string $name): bool
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
