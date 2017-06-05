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
class PluginRegistry extends ObjectRegistry implements EventDispatcherInterface
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
     * @param string $class The class to resolve.
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

        return $instance;

        /*
        if ($instance instanceof PluginInterface) {
            return $instance;
        }

        throw new RuntimeException(
            'Plugin handler must be set directly.'
        );
        */
    }

    /**
     * Get loaded plugin handler instance
     *
     * @param string $name
     * @return null|PluginInterface
     */
    public function get($name)
    {
        return parent::get($name);
    }

    /**
     * Attach event listeners and invoke the handler instance
     *
     * @param $name
     */
    public function run($name)
    {
        $inst = $this->get($name);

        if ($inst instanceof EventListenerInterface) {
            $this->eventManager()->on($inst);
        }

        if (is_callable($inst)) {
            call_user_func($inst);
        }
    }

    /**
     * Wrapper for creating and dispatching events.
     *
     * Returns a dispatched event.
     *
     * @param string $name Name of the event.
     * @param array|null $data Any value you wish to be transported with this event to
     * it can be read by listeners.
     * @param object|null $subject The object that this event applies to
     * ($this by default).
     *
     * @return \Cake\Event\Event
     */
    public function dispatchEvent($name, $data = null, $subject = null)
    {
        return $this->eventManager()->dispatch(new Event($name, $subject, $data));
    }

    /**
     * Returns the global Cake\Event\EventManager manager instance.
     *
     * @param \Cake\Event\EventManager|null $eventManager the eventManager to set
     * @return \Cake\Event\EventManager
     */
    public function eventManager(EventManager $eventManager = null)
    {
        return EventManager::instance();
    }
}
