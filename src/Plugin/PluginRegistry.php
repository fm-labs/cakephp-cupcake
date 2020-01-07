<?php
namespace Banana\Plugin;

use Banana\Exception\MissingPluginHandlerException;
use Cake\Core\ObjectRegistry;
use RuntimeException;

/**
 * Registry of loaded plugin handlers
 */
class PluginRegistry extends ObjectRegistry
{
    static public $fallbackPluginClass = "\\Banana\\Plugin\\GenericPlugin";

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
        throw new MissingPluginHandlerException(['class' => $class, 'plugin' => $plugin]);
    }

    /**
     * Should resolve the classname for a given object type.
     *
     * @param string $class The class to resolve.
     * @return string|false The resolved name or false for failure.
     */
    protected function _resolveClassName($class)
    {
        // Fallback if FALSE has been passed as a className
        if (!$class) {
            return self::$fallbackPluginClass;
        }

        if (is_string($class)) {
            // custom className
            if (class_exists($class)) {
                return $class;
            }
            // custom class not found
            //if (strpos($class, "\\") !== false) {
            //    return false;
            //}

            $plugin = $class;

            // Find plugin class by CakePHP convention: {PluginName}\\Plugin
            // Compatibility with CakePHP 3.6+
            $class = $plugin . '\\Plugin';
            if (class_exists($class)) {
                return $class;
            }

            // Find plugin class by Banana convention: {PluginName}\\{PluginName}Plugin
            $class = $plugin . '\\' . $plugin . 'Plugin';
            if (class_exists($class)) {
                return $class;
            }

            // Fallback
            return self::$fallbackPluginClass;
        }

        return $class;
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

        if ($instance instanceof PluginInterface) {
            return $instance;
        }

        throw new RuntimeException(
            'Plugin handler for ' . $alias . ' must be a subclass of \\Banana\\Plugin\\BasePlugin'
        );
    }

    /**
     * Get plugin handler instance.
     *
     * @param string $name Plugin name
     * @return null|PluginInterface
     */
    public function get($name)
    {
        return parent::get($name);
    }

    /**
     * Load plugin.
     * Injects plugin name into plugin config.
     *
     * @param string $objectName Plugin name
     * @param array $config Plugin config
     * @return null|PluginInterface
     */
    public function load($objectName, $config = [])
    {
        $config['name'] = $objectName;

        return parent::load($objectName, $config);
    }
}
