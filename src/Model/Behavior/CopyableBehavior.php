<?php
namespace Banana\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;

class CopyableBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'primaryKey' => 'id',
        'includeFields' => [],
        'excludeFields' => []
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
        // no op
    }

    /**
     * @param Entity $entity Entity to copy
     * @return Entity
     */
    public function copyEntity(Entity $entity)
    {
        $config = $this->config();



        $new = clone($entity);

        $new->{$config['primaryKey']} = null;
        $new->isNew(true);

        if (!empty($config['excludeFields'])) {
            foreach ($config['excludeFields'] as $field) {
                $new->set($field, null);
                //$new->dirty($field, false);
            }
        }

        return $new;
    }

    /**
     * @param Event $event The event
     * @param Entity $entity The entity
     * @return void
     */
    //public function beforeSave(Event $event, Entity $entity)
    //{
    //}
}
