<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * Class StatsBehavior
 * @package Banana\Model\Behavior
 */
class StatsBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'implementedMethods' => [
            'getStats' => 'getStats',
        ],
        'implementedFinders' => [],
    ];

    public function getStats()
    {
        $statFilters = [];
        $statFilters['count'] = function (Table $t) {
            return $t->find()->count();
        };

        $stats = [];
        foreach ($statFilters as $filterName => $filter) {
            try {
                if (!is_callable($filter)) {
                    throw new \InvalidArgumentException("Stats filter not callable");
                }

                $stats[$filterName] = $filter($this->_table);
            } catch (\Exception $ex) {
                $stats[$filterName] = ['error' => $ex->getMessage()];
            }
        }

        return $stats;
    }

    /**
     * @param array $config Behavior config
     * @return void
     */
    //public function initialize(array $config)
    //{
    //}

    /**
     * @param Event $event
     * @param Query $query
     * @param ArrayObject $options
     * @param bool $primary
     */
    //public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    //{
    //}

    /**
     * @param Event $event The event
     * @param Entity $entity The entity
     * @param \ArrayObject $options
     * @param $operation
     * @return void
     */
    //public function beforeRules(Event $event, Entity $entity, ArrayObject $options, $operation)
    //{
    //}

    /**
     * @param Event $event The event
     * @param Entity $entity The entity
     * @return void
     */
    //public function beforeSave(Event $event, Entity $entity)
    //{
    //}
}
