<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\MissingModelException;
use Cake\Event\Event;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Inflector;

/**
 * Class SortableBehavior
 *
 * @package Banana\Model\Behavior
 * @see http://book.cakephp.org/3.0/en/orm/behaviors.html
 */
class JsTreeBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'implementedFinders' => [
        ],
        'implementedMethods' => [
            'getJsTree' => 'getJsTree'
        ],
    ];

    /**
     * @param array $config Behavior config
     * @return void
     */
    public function initialize(array $config)
    {
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

    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
    }

    public function getJsTree($rootId = null)
    {
        if (!method_exists($this->_table, "toJsTree")) {
            if (Configure::read('debug')) {
                throw new MissingModelException(sprintf("Missing method 'jsTreeGetNodes' in model %s", $this->_table->alias()));
            }
            return [];
        }

        return call_user_func([$this->_table, 'toJsTree'], $rootId);
    }

    public function doSomething(EntityInterface $node)
    {
    }

    protected function _primaryKey()
    {
        $pk = $this->_table->primaryKey();
        return (is_array($pk)) ? $pk[0] : $pk;
    }
}
