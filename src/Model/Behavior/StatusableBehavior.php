<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Banana\Lib\Status;
use Cake\Collection\Iterator\MapReduce;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;

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
    protected $_defaultConfig = [
        'fields' => []
    ];

    protected $_fieldConfig = [];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
        //foreach($config['fields'] as $field => $stati) {
        //    $this->_configureField($field, $stati);
        //}

        if (!method_exists($this->_table, 'implementedStati')) {
            throw new \InvalidArgumentException(sprintf("Table %s has no method 'implementedStati'", $this->_table->alias()));
        }

        $this->_fieldConfig = call_user_func([$this->_table, 'implementedStati']);
    }

    protected function _configureField($field, array $stati)
    {
        foreach ($stati as $status => $conf) {
            $conf = array_merge(['label' => $status, 'class' => null], $conf);
            $this->_fieldConfig[$field][$status] = $conf;
        }
    }

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
     * @param Event $event
     * @param Query $query
     * @param array $options
     * @param $primary
     */
    /**
     * 'beforeFind' callback
     *
     * Applies a MapReduce to the query, which resolves attachment info
     * if an attachment field is present in the query results.
     *
     * @param Event $event
     * @param Query $query
     * @param array $options
     * @param $primary
     */
    public function beforeFind(Event $event, Query $query, $options, $primary)
    {
        //if (!isset($options['status']) || $options['status'] === false) {
        //    return;
        //}

        $mapper = function ($row, $key, MapReduce $mapReduce) {

            foreach (array_keys($this->_fieldConfig) as $fieldName) {

                if (!isset($row[$fieldName])) {
                    continue;
                }

                $rawVal = $row[$fieldName];
                foreach ($this->_fieldConfig[$fieldName] as $status) {
                    if ($status instanceof Status && $status->getStatus() == $rawVal) {
                        $row[$fieldName] = $status;
                        $row[$fieldName . '_raw'] = $rawVal;
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
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        foreach (array_keys($this->_fieldConfig) as $fieldName) {

            if ($entity->get($fieldName) === null) {
                continue;
            }

            // convert status object back to database type
            // and unset injected raw status field
            $status = $entity->get($fieldName);
            if ($status instanceof Status) {
                $raw = $entity->get($fieldName . '_raw');

                $entity->set($fieldName, $status->getStatus());
                if ($status->getStatus() == $raw) {
                    $entity->dirty($fieldName, false);
                }

                $entity->set($fieldName.'_raw', null);
                $entity->dirty($fieldName.'_raw', false);

            }


        }
    }
}
