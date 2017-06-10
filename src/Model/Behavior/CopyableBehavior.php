<?php
namespace Banana\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

/**
 * Class CopyableBehavior
 *
 * @package Banana\Model\Behavior
 */
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

        if (!empty($config['includeFields'])) {

            $new = $this->_table->newEntity();
            foreach ($config['includeFields'] as $field) {
                $new->set($field, $entity->get($field));
                //$new->dirty($field, false);
            }
        }
        elseif (!empty($config['excludeFields'])) {

            $new = clone($entity);
            foreach ($config['excludeFields'] as $field) {
                $new->set($field, null);
                //$new->dirty($field, false);
            }
        }

        $new->set($config['primaryKey'], null);
        $new->isNew(true);
        $new->clean();

        return $new;
    }
}
