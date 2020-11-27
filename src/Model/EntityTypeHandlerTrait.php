<?php
declare(strict_types=1);

namespace Cupcake\Model;

/**
 * Class EntityTypeHandlerTrait
 *
 * @package Cupcake\Model
 * @property string $_typeField Entity field that contains type info
 * @property string $_typeNamespace Entity type namespace
 * @property string $_typeInterface Entiy type interface className
 */
trait EntityTypeHandlerTrait
{
    //protected static $_typeField = 'type';
    //protected static $_typeNamespace = null;
    //protected static $_typeInterface = null;

    /**
     * @var \Cupcake\Model\EntityTypeInterface Type handler instance
     */
    private $_typeHandler;

    /**
     * @return \Cupcake\Model\EntityTypeInterface
     * @throws \Exception
     */
    protected function handler()
    {
        if ($this->_typeHandler === null) {
            $this->_typeHandler = $this->_createHandler();
        }

        return $this->_typeHandler;
    }

    /**
     * @return \Cupcake\Model\EntityTypeInterface
     * @throws \Exception
     */
    protected function _createHandler(): EntityTypeInterface
    {
        $ns = static::$_typeNamespace ?? static::class;
        $field = static::$_typeField ?? 'type';
        $iface = static::$_typeInterface ?? EntityTypeInterface::class;
        $type = $this->get($field) ?? 'default';
        if (!$type) {
            throw new \Exception(sprintf(
                "Can not resolve handler for unknown type in class '%s'",
                static::class
            ));
        }

        $handler = EntityTypeRegistry::createInstance($ns, $type, function ($className) {
            return new $className($this);
        });

        if ($iface && !($handler instanceof $iface)) {
            throw new \Exception(sprintf(
                "Can not create handler for type '%s':'%s' : Must implement interface '%s'",
                $ns,
                $type,
                $iface
            ));
        }

        return $handler;
    }
}
