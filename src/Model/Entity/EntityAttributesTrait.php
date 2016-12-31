<?php
namespace Banana\Model\Entity;


use Banana\Model\Behavior\AttributesBehavior;
use Cake\Collection\Collection;
use Cake\ORM\Exception\MissingBehaviorException;
use Cake\ORM\ResultSet;
use Cake\ORM\TableRegistry;

/**
 * Class EntityAttributesTrait
 * @package Banana\Model\Entity
 *
 * @property string $_registryAlias
 * @property array $_properties
 */
trait EntityAttributesTrait
{
    /**
     * @return AttributesBehavior
     */
    protected function _getAttributeableTable()
    {
        $Table = TableRegistry::get($this->_registryAlias);
        if (!$Table) {
            throw new \RuntimeException('Attributable table ' . $this->_registryAlias . ' not found');
        }

        if (!$Table->behaviors()->has('Attributes')) {
            throw new MissingBehaviorException(['behaviour' => 'Banana.Attributes']);
        }
        return $Table;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getAttribute($name = null)
    {
        if (!$name) {
            throw new \InvalidArgumentException('Attribute name missing');
        }

        $list = $this->getAttributesList()->toArray();
        if (array_key_exists($name, $list)) {
            return $list[$name];
        }

        return null;
    }

    /**
     * @return ResultSet
     */
    public function getAttributes()
    {
        if (!array_key_exists('attributes_modal_values', $this->_properties)) {
            $this->_properties['attributes_modal_values'] = $this->_getAttributeableTable()->getAttributes($this);
        }
        return $this->_properties['attributes_modal_values'];
    }

    /**
     * @return Collection
     */
    public function getAttributesList()
    {
        if (!array_key_exists('attributes_modal_values_list', $this->_properties)) {
            $this->_properties['attributes_modal_values_list'] = $this->_getAttributeableTable()->listAttributes($this);
        }
        return $this->_properties['attributes_modal_values_list'];
    }
}