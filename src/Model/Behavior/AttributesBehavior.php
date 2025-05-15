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
use Cupcake\Model\AttributesSchema;

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
    protected array $_defaultConfig = [
        'connectionName' => null,
        'tableClassName' => null,
        'tableName' => 'attributes',
        'propertyName' => 'attr',
        'relationName' => null,
        'relationPropertyName' => '__attributes',
        'schema' => [
            // 'foo' => ['type' => 'string', 'required' => true, 'default' => null]
        ],

        'implementedFinders' => [
            'withAttributes' => 'findWithAttributes',
            'byAttribute' => 'findByAttribute',
            'havingAttribute' => 'findHavingAttribute',
        ],
        'implementedMethods' => [
            'configureAttribute' => 'configureAttribute',
            'createAttribute' => 'createAttribute',
            'saveAttribute' => 'saveAttribute',
            'isAttributeKey' => 'isAttributeKey',
            'getAttributesTable' => 'attributesTable',
            'getAttributesTableAlias' => 'attributesTableAlias',
            'getAttributesSchema' => 'attributesSchema',
        ],
    ];

    /**
     * @var \Cupcake\Model\AttributesSchema Attributes schema description
     */
    protected $_schema;

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config): void
    {
        $attrTableAlias = $this->attributesTableAlias();
        /*
        if (!$this->_config['tableClassName']) {
            $attrTableConfig = [
                'table' => $this->_config['tableName'],
            ];

            $attrTableClass = new class ($attrTableConfig) extends Table {
                public function __construct(array $config = [])
                {
                    parent::__construct($config);
                }
            };

            $this->_config['tableClassName'] = $attrTableClass;
        }
        */
        $attrTableConfig = [
            'className' => $this->_config['tableClassName'],
            'table' => $this->_config['tableName'],
            'connectionName' => $this->_config['connectionName']
                ?? $this->_table->getConnection()->configName(),
        ];
        $attrTableInstance = TableRegistry::getTableLocator()->get($attrTableAlias, $attrTableConfig);

        $this->_table->hasMany($attrTableAlias, [
            //'className' => $this->_config['tableClassName'],
            'foreignKey' => 'foreign_key',
            'conditions' => [
                $attrTableAlias . '.model' => $this->_table->getRegistryAlias(),
            ],
            'dependent' => true,
            'propertyName' => $this->_config['relationPropertyName'],
            'targetTable' => $attrTableInstance,
        ]);
    }

    public function attributesTableAlias(): string
    {
        return $this->_config['relationName'] ?? $this->_table->getAlias() . 'Attributes';
    }

    /**
     * @return \Cake\ORM\Association
     */
    public function attributesTable()
    {
        return $this->_table->getAssociation($this->attributesTableAlias());
    }

    /**
     * @return array
     */
    public function attributesSchema()
    {
        if (!isset($this->_schema)) {
            // configured attributes for model
            $attributes = (array)$this->getConfig('schema');
            $schema = new AttributesSchema($attributes);

            // model buildAttributes callback
            if (method_exists($this->_table, 'buildAttributes')) {
                $schema = call_user_func([$this->_table, 'buildAttributes'], $schema);
            }

            // model event 'buildAttributes'
            $event = $this->_table->dispatchEvent('Model.buildAttributes', ['schema' => $schema]);
            $schema = $event->getData('schema') ?? $schema;

            $this->_schema = $schema;
        }

        return $this->_schema;
    }

    public function configureAttribute(string $attrName, array $options = [])
    {
        $this->attributesSchema()
            ->addAttribute($attrName, $options);

        return $this;
    }

    /**
     * Find or create an attribute for an entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity object
     * @param string $key Attribute key
     * @param mixed $value Attribute value
     * @return \Cake\Datasource\EntityInterface An Attribute entity
     */
    public function createAttribute(EntityInterface $entity, $key, $value = null)
    {
        return $this->attributesTable()->findOrCreate([
            'model' => $this->_table->getRegistryAlias(),
            'foreign_key' => $entity->id,
            'key' => $key,
        ], function ($attr) use ($value) {
            $attr->value = $value;

            return $attr;
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
        $query->contain([$this->attributesTableAlias()]);
        $query->formatResults([$this, 'formatAttributesResult']);

        return $query;
    }

    /**
     * Find rows by given attributes (key-value-pairs)
     *
     * @param \Cake\ORM\Query $query Query object
     * @param array $options List of attributes (key-value-pairs)
     * @return \Cake\ORM\Query
     */
    public function findByAttribute(Query $query, array $options = [])
    {
        if (empty($options)) {
            throw new \InvalidArgumentException('Attribute key-value pair(s) missing');
        }

        $attrsQuery = $this->attributesTable()->find();
        foreach ($options as $k => $v) {
            $cond = [
                $this->attributesTableAlias() . '.key' => $k,
                $this->attributesTableAlias() . '.value' => $v,
            ];
            if ($v === null) {
                unset($cond[$this->attributesTableAlias() . '.value']);
                $cond[$this->attributesTableAlias() . '.value IS'] = $v;
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
     * @param array $options List of attribute keys
     * @return \Cake\ORM\Query
     * @TODO Support for multiple attributes
     */
    public function findHavingAttribute(Query $query, array $options = [])
    {
        if (empty($options)) {
            throw new \InvalidArgumentException('Attribute key-value pair(s) missing');
        }

        $attrsQuery = $this->attributesTable()
            ->find()
            ->where(function (QueryExpression $exp, Query $query) use ($options) {
                return $exp->in('key', $options);
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
            if ($row->get($this->_config['relationPropertyName'])) {
                $col = collection($row->get($this->_config['relationPropertyName']));
                $attrs = $col->combine('key', 'value')->toArray();

                foreach ($attrs as $key => $value) {
                    $row->{$key} = $row->{$key} ?: $value;
                }

                $row->{$this->_config['propertyName']} = $attrs;
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

        $reducer = function ($bucket, $key, MapReduce $mapReduce) {
            $mapReduce->emit($bucket[0], $key);
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
     * @param string $key Validator key
     * @return void
     */
    public function buildValidator(EventInterface $event, Validator $validator, string $key): void
    {
        foreach ($this->attributesSchema()->getKeys() as $field) {
            $config = $this->attributesSchema()->getAttribute($field);

            $required = $config['required'] ?? false;
            $validator->requirePresence($field, $required);
            $validator->allowEmptyString($field);

            $fieldValidator = $config['validator'] ?? null;
            if (is_callable($fieldValidator)) {
                //$fieldValidator($validator, $key);
                call_user_func($fieldValidator, $validator, $key);
            }
        }
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data[$this->_config['propertyName']])) {
            foreach ($data[$this->_config['propertyName']] as $key => $val) {
                if ($this->isAttributeKey($key)) {
                    $data[$key] = $this->_marshal($key, $val);
                }
            }
            unset($data[$this->_config['propertyName']]);
        }
    }

    protected function _marshal(string $key, $val)
    {
        $attr = $this->attributesSchema()->getAttribute($key);
        switch ($attr['type'] ?? null) {
            case 'int':
                $val = intval($val);
                break;
            case 'float':
                $val = floatval($val);
                break;
            case 'string':
            default:
                $val = (string)$val;
                break;
        }

        return $val;
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): bool
    {
//        debug("beforeSave");
//        if ($entity->isDirty($this->_config['propertyName'])) {
//            debug("before save: attributes are dirty");
//            foreach ($entity->get($this->_config['propertyName']) as $key => $val) {
//                if ($this->isAttributeKey($key) && !$entity->has($key)) {
//                    $entity->set($key, $val);
//                }
//            }
//        }

        return true;
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
//        if ($entity->isDirty($this->_config['propertyName'])) {
//            //debug("attributes are dirty");
//            foreach ($entity->get($this->_config['propertyName']) as $key => $val) {
//                if ($this->isAttributeKey($key)) {
//                    $attr = $this->createAttribute($entity, $key);
//                    $attr->value = $val;
//                    if (!$this->saveAttribute($attr)) {
//                        debug("failed to save attr $key");
//                        $attr->setError('attributes_data', 'Error saving attr ' . $key);
//
//                        return false;
//                    }
//                }
//            }
//        }

        foreach ($entity->getDirty() as $key) {
            if ($this->isAttributeKey($key)) {
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
     * Check if a model's field is a registered attribute.
     *
     * @param string $key Attribute key
     * @return bool
     */
    public function isAttributeKey(string $key): bool
    {
        if ($key == $this->_config['propertyName']) {
            return false;
        }

        if ($this->_table->getSchema()->getColumn($key)) {
            return false;
        }

        return $this->attributesSchema()->hasAttribute($key);
    }
}
