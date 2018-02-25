<?php

namespace Banana\Plugin;

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


    public function __construct($plugins = null)
    {
        $this->_registry = new PluginRegistry();

        //@todo Use LocalEventManager instead
        $this->_eventManager = EventManager::instance();

        if ($plugins === null) {
            //@todo Move direct configuration access out of PluginManager scope. Assign empty array instead by default
            $plugins = (array) Configure::read('Banana.plugins')
                + (array) Configure::read('Plugin'); // legacy
        }
        $this->_plugins = $plugins;
    }

    public function implementedEvents()
    {
        return ['Banana.startup' => 'startup'];
    }

    /**
     * Load all available plugins with bootstrapping enabled, but routes disabled
     * Plugins should/can hook to Banana during their bootstrap process
     */
    public function bootstrap()
    {
        $defaultSettings = ['bootstrap' => true, 'routes' => false];

        // load and enable activated plugins
        foreach ($this->_plugins as $pluginName => $pluginSettings) {
            if (is_bool($pluginSettings)) {
                $pluginSettings = [];
            }
            $pluginSettings = array_merge($defaultSettings, $pluginSettings);

            $this->_loadPlugin($pluginName, $pluginSettings, false);
        }

        // Load all other available plugins, but with bootstrapping and routing disabled
        // this makes it easier to detect disabled/deactivated plugins
        // and avoids dependency issues as the class namespaces are still available
        // (e.g. if another plugin has a hard dependency on a deactivated plugin).
        // This behaviour might change in future versions.
        //Plugin::loadAll(['bootstrap' => false, 'routes' => false]);
    }

    public function startup(Event $event)
    {
        // enable all loaded plugins
        foreach (array_keys($this->_loaded) as $pluginName) {
            $this->enable($pluginName);
        }
    }

    public function load($plugin, array $pluginSettings = [])
    {
        $this->_loadPlugin($plugin, $pluginSettings, false);
    }

    protected function _loadPlugin($pluginName, array $pluginSettings = [], $enable = false)
    {
        if (isset($this->_loaded[$pluginName])) {
            return;
        }

        // load plugin
        $pluginSettings += ['ignoreMissing' => true];
        Plugin::load($pluginName, $pluginSettings);

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

            // enable-on-load
            if ($enable === true) {
                $this->enable($pluginName);
            }
        } catch (\Exception $ex) {
            Log::error("[PluginManager] PLUGIN_LOAD_FAILED " . $ex->getMessage(), ['banana', 'plugin']);
            //throw $ex;
            return;
        }
    }

    /**
     * Register a plugin handler
     */
    public function register($pluginName, array $settings = [])
    {
        return $this->_registry->load($pluginName, $settings);
    }

    /**
     * Enable plugin
     * Attaches plugin handler to event bus and invokes plugin handler
     */
    public function enable($pluginName)
    {
        if (isset($this->_enabled[$pluginName])) {
            return $this;
        }

        if (!$this->_registry->has($pluginName)) {
            throw new \Exception("Banana::enable: FAILED: Plugin $pluginName not registered");
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
    
}