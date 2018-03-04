<?php

namespace Banana\Plugin;

use Banana\Exception\MissingPluginHandlerException;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Utility\Inflector;

/**
 * Plugin Manager
 */
class PluginManager implements EventListenerInterface, EventDispatcherInterface
{
    use EventDispatcherTrait;

    /**
     * @var PluginRegistry
     */
    protected $_registry;

    /**
     * Array of plugin configurations
     * @var array
     */
    protected $_plugins = [];

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


    public function __construct(array $plugins = [])
    {
        $this->_registry = new PluginRegistry();

        //@todo Use LocalEventManager instead
        $this->_eventManager = EventManager::instance();

//        if ($plugins === null) {
//            //@todo Move direct configuration access out of PluginManager scope. Assign empty array instead by default
//            $plugins = (array) Configure::read('Banana.plugins')
//                + (array) Configure::read('Plugin'); // legacy
//        }
        $this->_plugins = $plugins;
    }

    public function implementedEvents()
    {
        return ['Banana.startup' => 'startup'];
    }

    public function startup(Event $event)
    {
        // load configured plugins
        $this->load($this->_plugins);

        // Load all other available plugins, but with bootstrapping and routing disabled
        // this makes it easier to detect disabled/deactivated plugins
        // and avoids dependency issues as the class namespaces are still available
        // (e.g. if another plugin has a hard dependency on a deactivated plugin).
        // This behaviour might change in future versions.
        //Plugin::loadAll(['bootstrap' => false, 'routes' => false]);

        // enable all loaded plugins
        foreach (array_keys($this->_loaded) as $pluginName) {
            $this->enable($pluginName);
        }
    }

    public function load($pluginName, $pluginSettings = [])
    {
        if (is_array($pluginName)) {
            foreach ($pluginName as $_pluginName => $_pluginSettings) {
                $this->load($_pluginName, (array) $_pluginSettings);
            }
            return;
        }

        $this->_loadPlugin($pluginName, $pluginSettings, false);

        return $this;
    }

    protected function _loadPlugin($pluginName, $pluginSettings = [], $enable = false)
    {
        if (isset($this->_loaded[$pluginName])) {
            return;
        }

        $defaultSettings = ['bootstrap' => true, 'routes' => false, 'ignoreMissing' => true];

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

        // load plugin config
        try {
            Configure::load('local/plugin/' . Inflector::underscore($pluginName));
        } catch (\Exception $ex) {
            try {
                Configure::load('plugin/' . Inflector::underscore($pluginName));
            } catch (\Exception $ex) {}
        }

        // load plugin handler
        try {
            $this->_registry->load($pluginName, $pluginSettings);
            $this->_loaded[$pluginName] = true;
            $this->_plugins[$pluginName] = $pluginSettings;

            // enable-on-load
            if ($enable === true) {
                $this->enable($pluginName);
            }
        } catch (MissingPluginHandlerException $ex) {
            // the plugin obviously has no plugin handler
            // so ignore this exception
        } catch (\Exception $ex) {
            Log::error("[PluginManager] PLUGIN_LOAD_FAILED " . $ex->getMessage(), ['banana', 'plugin']);
            //debug("PluginManager::_loadPlugin: PluginHandler error for $pluginName: " . $ex->getMessage());
            //throw $ex;
            return;
        }
    }

    /**
     * Registers a new plugin
     * @return $this
     */
    public function register($pluginName, array $settings = [])
    {
        if (is_array($pluginName)) {
            foreach ($pluginName as $_pluginName => $_settings) {
                $this->register($_pluginName, (array) $_settings);
            }
            return $this;
        }

        $this->_plugins[$pluginName] = $settings;

        return $this;
    }

    /**
     * Enable plugin
     * Attaches plugin handler to event bus and invokes plugin handler
     * @param $pluginName
     * @return $this
     * @throws \Exception
     */
    public function enable($pluginName)
    {
        if (isset($this->_enabled[$pluginName])) {
            return $this;
        }

        if (!$this->_registry->has($pluginName)) {
            throw new \Exception("PluginManager::enable: FAILED: Plugin $pluginName not registered");
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
     * @return object|null
     */
    public function get($pluginName)
    {
        //if (!$this->_registry->has($pluginName)) {
        //    throw new \Exception("PluginManger::get: FAILED: Plugin $pluginName not registered");
        //}

        return $this->_registry->get($pluginName);
    }

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
