<?php
namespace Banana\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
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
class SortableBehavior extends Behavior
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'implementedFinders' => [
            'sorted' => 'findSorted',
        ],
        'implementedMethods' => [
            'moveUp' => 'moveUp',
            'moveDown' => 'moveDown',
            'moveTop' => 'moveTop',
            'moveBottom' => 'moveBottom',
            'moveAfter' => 'moveAfter',
            'moveBefore' => 'moveBefore'
        ],
        'field' => 'pos', // the sort position field
        'scope' => [], // sorting scope
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

    public function findSorted(Query $query, array $options = [])
    {
        //$options += ['scope' => []];
        //$scope = ($options['scope']) ? $options['scope'] : $this->config('scope');
        $scope = (array) $this->config('scope');
        array_unshift($scope, $this->config('field'));

        $query->order($scope);
        return $query;
    }

    public function moveUp(EntityInterface $node, $number = 1)
    {
        $delta = max(0, $number);
        return $this->_table->connection()->transactional(function () use ($node, $delta) {
            //$this->_ensureFields($node);
            return $this->_moveByDelta($node, $delta);
        });
    }

    public function moveDown(EntityInterface $node, $number = 1)
    {
        $delta = max(0, $number) * -1;
        return $this->_table->connection()->transactional(function () use ($node, $delta) {
            //$this->_ensureFields($node);
            return $this->_moveByDelta($node, $delta);
        });
    }

    public function moveTop(EntityInterface $node)
    {
        return $this->_table->connection()->transactional(function () use ($node) {
            //$this->_ensureFields($node);
            return $this->_moveToPosition($node, 1);
        });
    }

    public function moveBottom(EntityInterface $node)
    {
        return $this->_table->connection()->transactional(function () use ($node) {
            //$this->_ensureFields($node);
            return $this->_moveToPosition($node, $this->_getMaxPos($node));
        });
    }

    public function moveAfter(EntityInterface $node, $targetId)
    {
        return $this->_table->connection()->transactional(function () use ($node, $targetId) {
            //$this->_ensureFields($node);

            $targetQuery = $this->_scoped($this->_table->query(), $node);
            $targetNode = $targetQuery
                ->hydrate(false)
                ->select($this->_config['field'])
                ->where([ 'id' => $targetId ])
                ->first();

            if (!$targetNode) {
                return false;
            }

            $targetPos = $targetNode[$this->_config['field']];
            //debug("Move after $targetId which will be $targetPos");
            return $this->_moveToPosition($node, $targetPos);
        });
    }

    public function moveBefore(EntityInterface $node, $targetId)
    {
        return $this->_table->connection()->transactional(function () use ($node, $targetId) {
            //$this->_ensureFields($node);

            $targetQuery = $this->_scoped($this->_table->query(), $node);
            $targetNode = $targetQuery
                ->hydrate(false)
                ->select($this->_config['field'])
                ->where([ 'id' => $targetId ])
                ->first();

            if (!$targetNode) {
                return false;
            }

            $maxPos = $this->_getMaxPos($node) - 1;
            $targetPos = max(1, min($targetNode[$this->_config['field']], $maxPos));
            //debug("Move before $targetId which will be Pos $targetPos | Max $maxPos");
            return $this->_moveToPosition($node, $targetPos);
        });
    }

    protected function _moveToPosition(EntityInterface $node, $newPos)
    {
        $sortField = $this->_config['field'];
        $pos = $node->get($sortField);
        $delta = $pos - $newPos;
        return $this->_moveByDelta($node, $delta);
    }

    protected function _moveByDelta(EntityInterface $node, $delta)
    {
        $sortField = $this->_config['field'];
        $pos = $node->get($sortField);

        $newPos = $pos - $delta;
        $newPos = max(1, $newPos);
        //debug("Move Pos $pos by delta $delta -> New position will be: $newPos");

        if ($delta == 0) {
            return $node;
        }
        
        $query = $this->_scoped($this->_table->query(), $node);
        $exp = $query->newExpr();
        $shift = 1;

        if ($delta < 0) {
            // move down
            $max = $this->_getMaxPos($node);
            $newPos = min($newPos, $max);

            $movement = clone $exp;
            $movement->add($sortField)->add("{$shift}")->type("-");

            $cond1 = clone $exp;
            $cond1->add($sortField)->add("{$pos}")->type(">");

            $cond2 = clone $exp;
            $cond2->add($sortField)->add("{$newPos}")->type("<=");

        } elseif ($delta > 0) {
            // move up
            $movement = clone $exp;
            $movement->add($sortField)->add("{$shift}")->type("+");

            $cond1 = clone $exp;
            $cond1->add($sortField)->add("{$pos}")->type("<");

            $cond2 = clone $exp;
            $cond2->add($sortField)->add("{$newPos}")->type(">=");
        }

        $where = clone $exp;
        $where->add($cond1)->add($cond2)->type("AND");

        $query->update()
            ->set($exp->eq($sortField, $movement))
            ->where($where);

        $query->execute()->closeCursor();

        $node->set($sortField, $newPos);
        return $this->_table->save($node);
    }

    protected function _getMaxPos(EntityInterface $node)
    {
        $sortField = $this->_config['field'];

        $query = $this->_scoped($this->_table->query(), $node);
        $res = $query->select([$sortField])->hydrate(false)->orderDesc($sortField)->first();
        return $res[$sortField];
    }

    protected function _scoped(Query $query, EntityInterface $node) {

        $scope = $this->_config['scope'];

        if ($scope) {
            $scopeData = $node->extract($scope);
            $query->where($scopeData);
        }

        return $query;
    }
}
