<?php
namespace Banana\Plugin;

use Banana\Exception\MissingPluginHandlerException;
use Cake\Core\ObjectRegistry;
use Cake\Event\Event;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use RuntimeException;

/**
 * Registry of loaded plugin handlers
 */
class PluginRegistry extends ObjectRegistry
{

    /**
     * Throws an exception when a plugin handler is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the plugin handler is missing in.
     * @return void
     * @throws MissingPluginHandlerException
     */
    protected function _throwMissingClassError($class, $plugin)
    {
        throw new MissingPluginHandlerException(sprintf('Could not load plugin handler class %s', $class));
    }

    /**
     * Should resolve the classname for a given object type.
     *
     * @param string $plugin The class to resolve.
     * @return string|false The resolved name or false for failure.
     */
    protected function _resolveClassName($plugin)
    {
        $class = $plugin . '\\' . $plugin . 'Plugin';

        return (class_exists($class)) ? $class : false;
    }

    /**
     * Create the plugin handler instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string|\Psr\Log\LoggerInterface $class The classname or object to make.
     * @param string $alias The alias of the object.
     * @param array $settings An array of settings to use for the plugin handler.
     * @return \Psr\Log\LoggerInterface The constructed plugin handler class.
     * @throws \RuntimeException when an object doesn't implement the correct interface.
     */
    protected function _create($class, $alias, $settings)
    {
        if (is_callable($class)) {
            $class = $class($alias);
        }

        if (is_object($class)) {
            $instance = $class;
        }

        if (!isset($instance)) {
            $instance = new $class($settings);
        }

        /*
        if ($instance instanceof PluginInterface) {
            return $instance;
        }

        throw new RuntimeException(
            'Plugin handler must be set directly.'
        );
        */
        return $instance;
    }

    /**
     * Get loaded plugin handler instance
     *
     * @param string $name
     * @return null|PluginInterface
     */
//    public function get($name)
//    {
//        return parent::get($name);
//    }
}
