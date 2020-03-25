<?php
declare(strict_types=1);

namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\ORM\Behavior;
use Cake\ORM\Query;

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
    //public function initialize(array $config)
    //{
    //}

    /**
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @return $this
     */
    public function findPublished(Query $query, array $options)
    {
        $statusField = $query->getRepository()->getAlias() . '.' . $this->getConfig('statusField');
        $options = array_merge([
            'published' => true,
        ], $options);

        return $query->where([$statusField => $options['published']]);
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cake\ORM\Query $query
     * @param \ArrayObject $options
     * @param bool $primary
     */
    public function beforeFind(\Cake\Event\EventInterface $event, Query $query, ArrayObject $options, $primary)
    {

        if (isset($options['published'])) {
            $this->findPublished($query, [
                'published' => $options['published'],
            ]);
        }
    }

    /**
     * @param \Cake\Event\Event $event The event
     * @param \Cake\ORM\Entity $entity The entity
     * @param \ArrayObject $options
     * @param $operation
     * @return void
     */
    //public function beforeRules(Event $event, Entity $entity, ArrayObject $options, $operation)
    //{
    //}

    /**
     * @param \Cake\Event\Event $event The event
     * @param \Cake\ORM\Entity $entity The entity
     * @return void
     */
    //public function beforeSave(Event $event, Entity $entity)
    //{
    //}
}
