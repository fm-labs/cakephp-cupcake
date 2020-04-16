<?php
declare(strict_types=1);

namespace Banana\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;

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
        'excludeFields' => [],
    ];

    /**
     * @param \Cake\Datasource\EntityInterface $entity Entity to copy
     * @return \Cake\Datasource\EntityInterface
     */
    public function copyEntity(EntityInterface $entity)
    {
        $config = $this->getConfig();

        $new = $this->_table->newEmptyEntity();
        if (!empty($config['includeFields'])) {
            foreach ($config['includeFields'] as $field) {
                $new->set($field, $entity->get($field));
                //$new->dirty($field, false);
            }
        }

        $new->set($config['primaryKey'], null);
        $new->isNew(true);
        $new->clean();

        return $new;
    }
}
