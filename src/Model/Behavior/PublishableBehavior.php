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
        $statusField = $this->config('statusField');
        $options = array_merge([
            'published' => true
        ], $options);
        return $query->where([$statusField => $options['published']]);
    }

    /**
     * Automatically slug when saving.
     *
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
     * Automatically slug when saving.
     *
     * @param Event $event The event
     * @param Entity $entity The entity
     * @return void
     */
    public function beforeSave(Event $event, Entity $entity)
    {
    }
}
