<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Inflector;

/**
 * Class PublishableBehavior
 * @package Banana\Model\Behavior
 */
class PublishableBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'statusField' => 'is_published', // the field to store published flag
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
    }

    public function findPublished(Query $query, array $options)
    {
        $statusField = $query->repository()->alias() . '.' . $this->config('statusField');
        $options = array_merge([
            'published' => true
        ], $options);
        return $query->where([$statusField => $options['published']]);
    }

    /**
     * @param Event $event
     * @param Query $query
     * @param ArrayObject $options
     * @param boolean $primary
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {

        if (isset($options['published'])) {
            $this->findPublished($query, [
                'published' => $options['published']
            ]);
        }
    }

    /**
     * @param Event $event The event
     * @param Entity $entity The entity
     * @param \ArrayObject $options
     * @param $operation
     * @return void
     */
    public function beforeRules(Event $event, Entity $entity, ArrayObject $options, $operation)
    {
    }


    /**
     * @param Event $event The event
     * @param Entity $entity The entity
     * @return void
     */
    public function beforeSave(Event $event, Entity $entity)
    {
    }
}
