<?php

namespace Banana\Plugin;

use Banana\Exception\MissingPluginHandlerException;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Core\StaticConfigTrait;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Utility\Inflector;

/**
 * Plugin Manager
 */
class PluginManager implements EventDispatcherInterface
{
    use StaticConfigTrait;

    use EventDispatcherTrait;

    /**
     * @var PluginRegistry
     */
    protected $_registry;

//    /**
//     * Array of plugin configurations
//     * @var array
//     */
//    protected $_plugins = [];

    /**
     * List of loaded plugins
     * @var array
     */
    protected $_loaded = [];

    /**
     * List of enabled plugins
     * @var array
     */
    protected $_enabled = [];

    public static function loadAll()
    {
        foreach (self::$_config as $plugin => $pluginConfig) {
            if (is_bool($pluginConfig)) {
                $pluginConfig = [];
            }

            try {
                $pluginConfig = array_merge(['bootstrap' => true, 'routes' => false, 'ignoreMissing' => true], $pluginConfig);
                //debug($plugin);
                //debug($pluginConfig);
                Plugin::load($plugin, $pluginConfig);

                // load plugin config
                try {
                    Configure::load('plugin/' . Inflector::underscore($plugin));
                } catch (\Exception $ex) {
                }
                try {
                    Configure::load('local/plugin/' . Inflector::underscore($plugin));
                } catch (\Exception $ex) {
                }
            } catch (\Exception $ex) {
                Log::error('PluginManager: Failed loading plugin ' . $plugin . ':' . $ex->getMessage());
            }
        }
    }

    public function __construct(EventManager $eventManager)
    {
        $this->_registry = new PluginRegistry();

        $this->eventManager($eventManager);
    }

    public function load($pluginName, $pluginSettings = [], $enable = false)
    {
        if (is_array($pluginName)) {
            foreach ($pluginName as $_pluginName => $_pluginSettings) {
                $this->load($_pluginName, (array)$_pluginSettings, $enable);
            }

            return $this;
        }

        if (isset($this->_loaded[$pluginName])) {
            return $this;
        }

        $defaultSettings = ['bootstrap' => true, 'routes' => true, 'ignoreMissing' => true];

        // load plugin
        // if the plugin has been loaded manually before, we won't load it again
        if (!Plugin::loaded($pluginName)) {
            if (is_bool($pluginSettings)) {
                $pluginSettings = [];
            }
            $pluginSettings = array_merge($defaultSettings, $pluginSettings);
            Plugin::load($pluginName, $pluginSettings);
        } else {
            //debug("PluginManager::_loadPlugin: Plugin $pluginName already loaded");
        }
        $this->_loaded[$pluginName] = true;

        if ($enable === true) {
            return $this->enable($pluginName);
        }

        return $this;
    }

//    /**
//     * Registers a new plugin.
//     * This method has no effect if called after the startup event has been triggered.
//     * @return $this
//     */
//    public function register($pluginName, array $settings = [])
//    {
//        if (is_array($pluginName)) {
//            foreach ($pluginName as $_pluginName => $_settings) {
//                $this->register($_pluginName, (array) $_settings);
//            }
//            return $this;
//        }
//
//        $this->_plugins[$pluginName] = $settings;
//
//        return $this;
//    }

    /**
     * Enable plugin
     * Attaches plugin handler to event bus and invokes plugin handler
     * @param $pluginName
     * @return $this
     * @throws \Exception
     */
    public function enable($pluginName)
    {
        if (is_array($pluginName)) {
            foreach ($pluginName as $_plugin) {
                $this->enable($_plugin);
            }

            return $this;
        }

        if (isset($this->_enabled[$pluginName])) {
            return $this;
        }

        if (!$this->_registry->has($pluginName)) {
            // load plugin handler
            try {
                $this->_registry->load($pluginName);

                // load plugin config
                try {
                    Configure::load('plugin/' . Inflector::underscore($pluginName));
                } catch (\Exception $ex) {
                }
                try {
                    Configure::load('local/plugin/' . Inflector::underscore($pluginName));
                } catch (\Exception $ex) {
                }
            } catch (MissingPluginHandlerException $ex) {
                // the plugin obviously has no plugin handler
                // so ignore this exception
                //debug($ex->getMessage());
            } catch (\Exception $ex) {
                Log::error("[PluginManager] PLUGIN_LOAD_FAILED " . $ex->getMessage(), ['banana', 'plugin']);
                //debug("PluginManager::_loadPlugin: PluginHandler error for $pluginName: " . $ex->getMessage());
                //throw $ex;
            }
        }

        $plugin = $this->_registry->get($pluginName);

        if ($plugin instanceof EventListenerInterface) {
            $this->eventManager()->on($plugin);
        }

        if (is_callable($plugin)) {
            call_user_func($plugin, $this->eventManager());
        }

        $this->_enabled[$pluginName] = true;

        return $this;
    }

    public function disable($pluginName)
    {
        if (!isset($this->_enabled[$pluginName])) {
            //throw new \Exception("Banana::disable: FAILED: Plugin $pluginName not enabled");
            return $this;
        }

//        $config = $this->_enabled[$pluginName];
//        if ($config['protected'] === true) {
//            throw new \Exception("Banana::deenable: FAILED: Plugin $pluginName is protected");
//        }

        $plugin = $this->_registry->get($pluginName);
        if ($plugin instanceof EventListenerInterface) {
            $this->eventManager()->off($plugin);
        }

        $this->_enabled[$pluginName] = false;

        return $this;
    }

    /**
     * Get plugin handler object
     * @return object|null
     */
    public function get($pluginName)
    {
        //if (!$this->_registry->has($pluginName)) {
        //    //throw new \Exception("PluginManger::get: FAILED: Plugin $pluginName not registered");
        //    return null;
        //}

        return $this->_registry->get($pluginName);
    }

    /**
     * Get plugin info
     * @return array
     */
    public function getInfo($pluginName)
    {
        $info = [];
        $info['name'] = $pluginName;
        $info['loaded'] = Plugin::loaded($pluginName);
        $info['path'] = Plugin::path($pluginName);
        $info['config'] = Plugin::configPath($pluginName);
        $info['classPath'] = Plugin::classPath($pluginName);
        $info['registered'] = isset($this->_plugins[$pluginName]);
        $info['handler_loaded'] = $this->loaded($pluginName);
        $info['handler_enabled'] = $this->enabled($pluginName);

        return $info;
    }

    /**
     * Get list of loaded plugins or check loaded state of specific plugin by name
     * @param string|null Plugin name
     * @return bool|array
     */
    public function loaded($pluginName = null)
    {
        if ($pluginName !== null) {
            return (isset($this->_loaded[$pluginName])) ? true : false;
        }

        return array_keys($this->_loaded);
    }

    /**
     * Get list of loaded plugins or check enabled state of specific plugin by name
     * @param string|null Plugin name
     * @return bool|array
     */
    public function enabled($pluginName = null)
    {
        if ($pluginName !== null) {
            return (isset($this->_enabled[$pluginName])) ? true : false;
        }

        return array_keys($this->_enabled);
    }
}
