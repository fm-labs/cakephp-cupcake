<?php

namespace Banana\Plugin;

use Banana\Exception\MissingPluginConfigException;
use Banana\Exception\MissingPluginHandlerException;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Utility\Inflector;

class PluginLoader extends Plugin
{

    /**
     * @var PluginRegistry
     */
    static protected $_registry;

    /**
     * Load and normalize plugin configurations
     * @todo Config caching
     */
    protected static function _loadConfig()
    {
        // Check if we already loaded Banana plugin configurations
        if (Configure::check('Banana.plugins')) {
            return;
        }

        // Load CakePHP plugins (reads plugin paths from vendor/cakephp-plugins.php)
        parent::_loadConfig();

        $plugins = Cache::read('plugins', 'banana');
        //$plugins = [];
        if (!$plugins || Configure::read('Banana.disablePluginCache')) {

            // if no custom Plugin configuration has been defined, attempt to find plugin config file
            if (!Configure::check('Plugin')) {
                // the first available config file will be used and others ignored
                foreach(['local/plugins', 'plugins'] as $config) {
                    try {
                        Configure::load($config);
                        break;
                    } catch(\Exception $ex) {}
                }
            }

            // list of plugins has been intialized
            $plugins = (array) Configure::consume('Plugin');

            // normalize plugin configurations
            $defaultConfig = ['enabled' => false, 'bootstrap' => true, 'routes' => true];
            foreach ($plugins as $plugin => &$pluginConfig) {
                if (is_bool($pluginConfig)) { // Boolean value maps to enabled state
                    $pluginConfig = array_merge($defaultConfig, ['enabled' => $pluginConfig]);
                } elseif (is_array($pluginConfig)) {
                    $pluginConfig = array_merge($defaultConfig, $pluginConfig);
                } else {
                    throw new \InvalidArgumentException(sprintf("Plugin config for plugin '%s' MUST be array or boolean value", $plugin));
                }
            }

            Cache::write('plugins', $plugins, 'banana');
        } else {
            // If we got cached plugin configs, there might be a 'Plugin' config key set (e.g. from custom config files)
            // so we clear that config key
            Configure::delete('Plugin');
        }

        // write runtime configuration
        Configure::write('Banana.plugins',  $plugins);

        // init plugin registry
        static::$_registry = new PluginRegistry();
        static::$_registry->load('Banana');
    }

    /**
     * Activate plugin
     */
    static public function activate($plugin = null)
    {
        if (!Configure::check('Banana.plugins.'.$plugin)) {
            throw new MissingPluginConfigException(['plugin' => $plugin]);
        }
        // update enabled state to TRUE
        Configure::write('Banana.plugins.'.$plugin.'.enabled', true);
        Cache::delete('plugins', 'banana');

        $localPluginConfigFile = CONFIG . DS . 'local' . DS . 'plugins.php';
        $config = ['Plugin' => Configure::read('Banana.plugins')];
        self::_writePhpConfig($localPluginConfigFile, $config);
    }

    /**
     * Getter / Setter for plugin handlers
     *
     * @param $plugin
     * @param object|null $handler
     * @return PluginInterface|null
     */
    static public function handler($plugin, object $handler = null)
    {
        if ($handler === null) {
            return static::$_registry->get($plugin);
        }

        static::$_registry->set($plugin, $handler);
    }

    /**
     * Invoke all plugin handlers
     */
    static public function runAll()
    {
        foreach (static::$_registry->loaded() as $plugin) {
            static::$_registry->run($plugin);
        }
    }

    /**
     * Load all configured plugins
     *
     * @param array $options
     * @throws \Exception
     */
    static public function loadAll(array $options = [])
    {
        self::_loadConfig();
        foreach(Configure::read('Banana.plugins') as $pluginName => $pluginConfig) {
            static::load($pluginName, $pluginConfig);
        }
    }

    /**
     * Load banana plugin
     *
     * @param array|string $plugin
     * @param array $config
     * @throws \Exception
     * @return void
     */
    public static function load($plugin, array $config = [])
    {
        $defaultConfig = [
            'enabled' => true,
            'configs' => true,
            'bootstrap' => true,
            'routes' => true,
            'ignoreMissing' => true,
        ];
        $config = array_merge($defaultConfig, $config);

        $enabled = $config['enabled'];
        unset($config['enabled']);

        $loadConfig = $config['configs'];
        unset($config['configs']);

        // disable routes for disabled plugins
        if ($enabled !== true) {
            //$config['bootstrap'] = false;
            //$config['routes'] = false;
            return;
        }

        try {
            parent::load($plugin, $config);

            try {
                static::$_registry->load($plugin, $config);
            } catch (MissingPluginHandlerException $ex) {}

            // autoload local plugin configs
            if ($loadConfig === true) {
                static::_autoloadPluginConfig($plugin);
            }

        } catch (\Exception $ex) {
            Log::error(__CLASS__ . ': ' . $ex->getMessage());
            $config += ['enabled' => false, 'error' => $ex->getMessage()];

            if (Configure::read('debug')) {
                throw $ex;
            }
        }

        Configure::write('Plugin.' . $plugin, $config);
    }

    /**
     * Attempt to find a plugin config in app configs.
     * All found configs will be merged.
     *
     * @param $plugin
     */
    static protected function _autoloadPluginConfig($plugin)
    {
        $_underscored = Inflector::underscore($plugin);
        $configFiles = [
            $_underscored, // from local config folder
            'plugin/' . $_underscored, // from local plugins config folder
            'local/' . $_underscored // from local config folder
        ];
        foreach ($configFiles as $configFile) {
            if (file_exists(CONFIG . $configFile . '.php')) {
                Configure::load($configFile);
            }
        }
    }

    /**
     * @param $path
     * @return mixed
     */
    static protected function _readPhpConfig($path)
    {
        $loader = function() use ($path) {
            if (!file_exists($path)) {
                return false;
            }

            $config = include $path;
            return $config;
        };
        return $loader();
    }

    /**
     * @param $path
     * @param array $data
     * @return int
     */
    static protected function _writePhpConfig($path, array $data)
    {
        $contents = '<?php' . "\n" . 'return ' . var_export($data, true) . ';' . "\n";
        return file_put_contents($path, $contents);
    }
}