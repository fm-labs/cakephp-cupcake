<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;

/**
 * Class SluggableBehavior
 *
 * @package Banana\Model\Behavior
 * @see http://book.cakephp.org/3.0/en/orm/behaviors.html
 */
class PageBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
    }

    // In a table or behavior class
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
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
