<?php

namespace Banana\Model;

use Banana\Lib\ClassRegistry;
use Cake\Utility\Inflector;

/**
 * Class EntityTypeHandlerTrait
 *
 * @package Banana\Model
 */
trait EntityTypeHandlerTrait
{
    //protected $_typeField = 'type';
    //protected $_typeNamespace = null;

    /**
     * @var EntityTypeInterface Type handler instance
     */
    protected $_typeHandler;

    /**
     * @return EntityTypeInterface
     * @throws \Exception
     */
    protected function handler()
    {
        if ($this->_typeHandler === null) {
            //if (!($this instanceof EntityInterface)) {
            //    throw new \Exception(sprintf("EntityTypeHandler can only be applied to an instance of EntityInterface"));
            //}
            $type = $this->_getHandlerType();
            if (!$type) {
                throw new \Exception(sprintf('Type handler can not be attached without type for ' . get_class($this) . ' with id ' . $this->id));
            }

            $handler = $this->_createHandler($type);

            if (!($handler instanceof EntityTypeInterface)) {
                throw new \Exception(sprintf("Type handler MUST be an instance of EntityTypeInterface"));
            }

            $this->_typeHandler = $handler;
            //$this->_typeHandler->setEntity($this);
        }

        return $this->_typeHandler;
    }

    protected function _createHandler($type)
    {
        $ns = $this->_getHandlerNamespace();
        if (!$ns) {
            throw new \InvalidArgumentException('Type handler namespace not defined');
        }
        //$handler = ClassRegistry::get($ns, $type);
        $handlerClass = ClassRegistry::getClass($ns, $type);
        if (!$handlerClass) {
            throw new \Exception("No type handler class for $ns:$type:$handlerClass");
        }
        if (!class_exists($handlerClass)) {
            throw new \Exception("Type handler class not found $ns:$type");
        }

        $handler = new $handlerClass($this);

        return $handler;
    }

    /**
     * Get entity type handler alias
     * Override method in entity classes to customize handler type getter.
     *
     * By default the value of the entity field 'type' will be evaluated
     *
     * @return mixed
     */
    protected function _getHandlerType()
    {
        return ($this->get('type')) ?: 'default';
    }

    /**
     * Set Accessor for property 'type'
     *
     * @param $val
     * @return mixed
     */
    protected function _setType($val)
    {
        // reset type handler
        $this->_typeHandler = null;

        return $val;
    }

    /**
     * Get entity type namespace
     * Override method in entity classes to customize handler namespace getter.
     *
     * Defaults to Entity class name with 'Type' suffix
     * e.g. for Model Blog.Posts -> 'PostType'
     *
     * @return string ClassRegistry namespace
     */
    protected function _getHandlerNamespace()
    {
        list(, $class) = namespaceSplit(get_class($this));
        $ns = Inflector::camelize(Inflector::singularize($class)) . 'Type';

        return $ns;
    }
}
