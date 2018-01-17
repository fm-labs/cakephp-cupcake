<?php

namespace Banana\Plugin;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Utility\Inflector;

/**
 * Plugin Manager
 * //@TODO Implement 'core' and 'protected' plugin directives
 */
class PluginManager implements EventListenerInterface, EventDispatcherInterface
{
    use EventDispatcherTrait;

    /**
     * @var PluginRegistry
     */
    protected $_registry;

    /**
     * List of enabled plugins
     */
    protected $_enabled = [];

    /**
     * Core plugins config
     */
    protected static $_corePlugins = [
        'Banana' => ['protected' => true],
        'Backend' => ['protected' => true],
        'User' => ['protected' => true],
        'Settings' => ['protected' => true],
    ];
    
    public function __construct()
    {
        $this->_registry = new PluginRegistry();
        $this->_eventManager = EventManager::instance();
    }

    public function implementedEvents()
    {
        return ['Banana.init' => 'init'];
    }

    public function init()
    {
        $this->_loadCorePlugins();
        $this->_loadUserPlugins();
    }

    protected function _getActivated()
    {
        return (array) Configure::read('Banana.plugins')
            + (array) Configure::read('Plugin'); // legacy
    }

    /**
     * Load core plugins with bootstrapping and routes enabled
     * and auto-enable the plugin
     */
    protected function _loadCorePlugins()
    {
        foreach (self::$_corePlugins as $pluginName => $pluginSettings) {
            Plugin::load($pluginName, ['bootstrap' => true, 'routes' => true]);
            $this->_registry->load($pluginName, $pluginSettings);
            $this->enable($pluginName);
        }
    }

    /**
     * Load all available plugins with bootstrapping enabled, but routes disabled
     * Plugins should/can hook to Banana during their bootstrap process
     */
    protected function _loadUserPlugins()
    {
        $defaultSettings = ['bootstrap' => true, 'routes' => false];

        // load and enable activated plugins
        foreach ($this->_getActivated() as $pluginName => $pluginSettings) {
            if (is_bool($pluginSettings)) {
                $pluginSettings = [];
            }

            $pluginSettings = array_merge($defaultSettings, $pluginSettings);
            Plugin::load($pluginName, $pluginSettings);

            $this->_registry->load($pluginName, $pluginSettings);
            $this->enable($pluginName);
        }

        // load all other available plugins, but with bootstrapping and routing disabled
        Plugin::loadAll(['bootstrap' => false, 'routes' => false]);
    }

    public function register($pluginName, array $settings = [])
    {

        return $this->_registry->load($pluginName, $settings);
    }

    public function enable($pluginName)
    {
        if (!$this->_registry->has($pluginName)) {
            throw new \Exception("Banana::enable: FAILED: Plugin $pluginName not registered");
        }

        $plugin = $this->_registry->get($pluginName);

        // load plugin config
        try {
            Configure::load('plugin/' . Inflector::underscore($pluginName));
        } catch (\Exception $ex) {}

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