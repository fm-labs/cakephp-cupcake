<?php
declare(strict_types=1);

namespace Cupcake\Model\Behavior;

use ArrayObject;
use Cake\Collection\Iterator\MapReduce;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cupcake\Lib\Status;

/**
 * Statusable behavior
 */
class StatusableBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $_defaultConfig = [
        'fields' => [],
        'implementedMethods' => [
            'getStatusCodes' => 'getStatusCodes',
            'getStatusList' => 'getStatusList',
            'getStatusSelectList' => 'getStatusSelectList',
        ],
    ];

    /**
     * @var array
     */
    protected $_fieldConfig = [];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config): void
    {
        //foreach($config['fields'] as $field => $stati) {
        //    $this->_configureField($field, $stati);
        //}

        if (!method_exists($this->_table, 'implementedStati')) {
            throw new \InvalidArgumentException(sprintf("Table %s has no method 'implementedStati'", $this->_table->getAlias()));
        }

        $this->_fieldConfig = call_user_func([$this->_table, 'implementedStati']);
    }

    /**
     * @param $field
     * @param array $stati
     */
    protected function _configureField($field, array $stati)
    {
        foreach ($stati as $status => $conf) {
            $conf = array_merge(['label' => $status, 'class' => null], $conf);
            $this->_fieldConfig[$field][$status] = $conf;
        }
    }

    /**
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findStatus(Query $query, array $options)
    {
        return $query;
    }

    /**
     * 'beforeFind' callback
     *
     * Applies a MapReduce to the query, which resolves attachment info
     * if an attachment field is present in the query results.
     *
     * @param \Cake\Event\Event $event
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @param $primary
     */

    /**
     * 'beforeFind' callback
     *
     * Applies a MapReduce to the query, which resolves attachment info
     * if an attachment field is present in the query results.
     *
     * @param \Cake\Event\Event $event
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @param $primary
     */
    public function beforeFind(\Cake\Event\EventInterface $event, Query $query, $options, $primary)
    {
        if (!isset($options['status']) || $options['status'] === false) {
            return;
        }

        $mapper = function ($row, $key, MapReduce $mapReduce) {

            foreach (array_keys($this->_fieldConfig) as $fieldName) {
                if (!isset($row[$fieldName])) {
                    continue;
                }

                $rawVal = $row[$fieldName];
                foreach ($this->_fieldConfig[$fieldName] as $status) {
                    if ($status instanceof Status && $status->getStatus() == $rawVal) {
                        $row[$fieldName] = $rawVal;
                        $row[$fieldName . '__status'] = $status;
                        break;
                    }
                }
            }

            $mapReduce->emitIntermediate($row, $key);
        };

        $reducer = function ($bucket, $name, MapReduce $mapReduce) {
            $mapReduce->emit($bucket[0], $name);
        };

        $query->mapReduce($mapper, $reducer);
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cake\Datasource\EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function beforeSave(\Cake\Event\EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        foreach (array_keys($this->_fieldConfig) as $fieldName) {
            if ($entity->get($fieldName) === null) {
                continue;
            }

            // convert status object back to database type
            // and unset injected raw status field
            $status = $entity->get($fieldName);
            if ($status instanceof Status) {
                $entity->set($fieldName . '__original', null);
                $entity->setDirty($fieldName . '__original', false);
                $entity->set($fieldName, $status->getStatus());
            }
        }
    }

    public function getStatusCodes(string $field) {
        $fieldStati = $this->_fieldConfig[$field] ?? null;
        if ($fieldStati === null) {
            return [];
        }

        return array_keys($fieldStati);
    }

    public function getStatusList(string $field) {
        $fieldStati = $this->_fieldConfig[$field] ?? null;
        //var_dump($fieldStati);
        if ($fieldStati === null) {
            return [];
        }

        return array_map(function($status) {
            /** @var \Cupcake\Lib\Status $status */
            return $status->getLabel();
        }, $fieldStati);
    }

    public function getStatusSelectList(string $field) {
        $fieldStati = $this->_fieldConfig[$field] ?? null;
        //var_dump($fieldStati);
        if ($fieldStati === null) {
            return [];
        }

        $selectList = [];
        foreach ($fieldStati as $status) {
            $selectList[$status->getStatus()] = $status->getLabel();
        }
        return $selectList;
    }
}
