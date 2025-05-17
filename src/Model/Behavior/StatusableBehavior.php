<?php
declare(strict_types=1);

namespace Cupcake\Model\Behavior;

use ArrayAccess;
use Cake\Collection\Iterator\MapReduce;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Query\SelectQuery;
use Cupcake\Lib\Status;
use InvalidArgumentException;
use function Cake\Core\deprecationWarning;

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
        'implementedFinders' => [
            'status' => 'findStatus',
        ],
        'implementedMethods' => [
            'getStatusCodes' => 'getStatusCodes',
            'getStatusList' => 'getStatusList',
            'getStatusSelectList' => 'getStatusSelectList',
        ],
    ];

    /**
     * @var array
     */
    protected array $_fieldConfig = [];

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
            throw new InvalidArgumentException(sprintf(
                "Table %s has no method 'implementedStati'",
                $this->_table->getAlias(),
            ));
        }

        $this->_fieldConfig = call_user_func([$this->_table, 'implementedStati']);
    }

    /**
     * @param string $field
     * @param array $statusMap
     */
    protected function _configureField(string $field, array $statusMap): void
    {
        foreach ($statusMap as $status => $conf) {
            $conf = array_merge(['label' => $status, 'class' => null], $conf);
            $this->_fieldConfig[$field][$status] = $conf;
        }
    }

    /**
     * Attach status info to the query results
     *
     * Applies a MapReduce to the query, which resolves status info
     * if a status field is present in the query results.
     *
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findStatus(Query $query, array $options): Query
    {
        $mapper = function ($row, $key, MapReduce $mapReduce): void {

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

        $reducer = function ($bucket, $name, MapReduce $mapReduce): void {
            $mapReduce->emit($bucket[0], $name);
        };

        $query->mapReduce($mapper, $reducer);

        return $query;
    }


    /**
     * @param \Cake\Event\Event $event
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param \ArrayAccess|array $options
     * @param bool $primary
     */
    public function beforeFind(EventInterface $event, SelectQuery $query, ArrayAccess|array $options, bool $primary): void
    {
        if (isset($options['status']) && $options['status'] === true) {
            deprecationWarning(
                '4.0.1',
                'The status query option is deprecated. Use the StatusableBehavior::findStatus() method instead.',
            );
            unset($options['status']);
            $query->find('status'/*, (array)$options*/);
        }
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cake\Datasource\EntityInterface $entity
     * @param \ArrayAccess|array $options
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayAccess|array $options): void
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

    /**
     * @param string $field
     * @return array
     */
    public function getStatusCodes(string $field): array
    {
        $fieldStati = $this->_fieldConfig[$field] ?? null;
        if ($fieldStati === null) {
            return [];
        }

        return array_keys($fieldStati);
    }

    /**
     * @param string $field
     * @return array
     */
    public function getStatusList(string $field): array
    {
        $fieldStati = $this->_fieldConfig[$field] ?? null;
        //var_dump($fieldStati);
        if ($fieldStati === null) {
            return [];
        }

        return array_map(function ($status) {
            /** @var \Cupcake\Lib\Status $status */
            return $status->getLabel();
        }, $fieldStati);
    }

    /**
     * @param string $field
     * @return array
     */
    public function getStatusSelectList(string $field): array
    {
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
