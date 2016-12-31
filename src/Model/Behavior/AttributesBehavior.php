<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/30/16
 * Time: 8:11 PM
 */

namespace Banana\Model\Behavior;


use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class AttributesBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'implementedFinders' => [
            'attributes' => 'findAttributes',
        ],
        'implementedMethods' => [
            'getAttributes' => 'getAttributes',
            'listAttributes' => 'getAttributesList',
            'getAvailableAttributes' => 'getAvailableAttributes',
            'listAvailableAttributes' => 'getAvailableAttributesList'
        ],
        'attributes' => []
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
    }

    public function findAttributes(Query $query, array $options = [])
    {
        $this->_table->hasMany('AttributesModelValues', [
            'className' => 'Banana.AttributesModelValues',
            'foreignKey' => 'modelid',
            'conditions' => ['model' => $this->_table->alias()]
        ]);
        $query->contain(['AttributesModelValues' => ['Attributes']]);
        return $query;
    }

    /**
     * @param EntityInterface $entity
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function getAttributes(EntityInterface $entity)
    {
        return TableRegistry::get('Banana.AttributesModelValues')->find()
            ->contain(['Attributes'])
            ->where(['AttributesModelValues.model' => $this->_table->alias(), 'AttributesModelValues.modelid' => $entity->get('id') ])
            ->all();
    }

    /**
     * @param EntityInterface $entity
     * @return \Cake\Collection\CollectionInterface
     */
    public function getAttributesList(EntityInterface $entity)
    {
        $attributes = $this->getAttributes($entity);
        return $attributes->combine('attribute.name', 'value');
    }

    public function getAvailableAttributes(EntityInterface $entity)
    {
        return $this->config('attributes');
    }

    public function getAvailableAttributesList(EntityInterface $entity)
    {
        $available = $this->getAvailableAttributes($entity);
        $list = [];
        foreach ($available as $attr => $attrConfig) {
            $attrConfig += ['type' => null];

            $list[$attr] = $attrConfig['type'];
        }
        return $list;
    }
}