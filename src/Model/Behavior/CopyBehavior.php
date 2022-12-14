<?php
declare(strict_types=1);

namespace Cupcake\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;

/**
 * Class CopyBehavior
 *
 * @package Cupcake\Model\Behavior
 */
class CopyBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [],
        'validator' => 'default',
        'implementedMethods' => [
            'copyEntity' => 'copyEntity',
            'copy' => 'copy',
        ],
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config): void
    {
        // nothing to initialize
    }

    /**
     * Copy entity.
     * @param \Cake\Datasource\EntityInterface $entity Entity to copy
     * @return \Cake\Datasource\EntityInterface
     */
    public function copyEntity(EntityInterface $entity)
    {
        $config = $this->getConfig();

        $new = $this->_table->newEmptyEntity();
        if (!empty($config['fields'])) {
            foreach ($config['fields'] as $field) {
                $new->set($field, $entity->get($field));
            }
        }

        $new->setNew(true);
        $new->set($this->_table->getPrimaryKey(), null);

        return $new;
    }

    /**
     * Copy entity object and insert as new row.
     * @param \Cake\Datasource\EntityInterface $entity The entity to copy
     * @param array $options Model save options
     * @return \Cake\Datasource\EntityInterface|false
     */
    public function copy(EntityInterface $entity, array $options = [])
    {
        return $this->_table->save($this->copyEntity($entity), $options);
    }
}
