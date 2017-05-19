<?php

namespace Banana\Plugin;

use Banana\Exception\MissingPluginConfigException;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
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

        // @TODO read plugins config to cache

        // Load CakePHP plugins (reads plugin paths from vendor/cakephp-plugins.php)
        parent::_loadConfig();


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

        // write runtime configuration
        // @TODO write plugins config to cache
        Configure::write('Banana.plugins',  $plugins);

        // init plugin registry
        static::$_registry = new PluginRegistry();
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

        $localPluginConfigFile = CONFIG . DS . 'local' . DS . 'plugins.php';
        $config = ['Plugin' => Configure::read('Banana.plugins')];
        self::_writePhpConfig($localPluginConfigFile, $config);
    }

    static public function handler($plugin, object $handler = null)
    {
        if ($handler === null) {
            return static::$_registry->get($plugin);
        }

        static::$_registry->set($plugin, $handler);
    }

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
     * @param array|string $plugin
     * @param array $config
     * @throws \Exception
     * @return void
     */
    public static function load($plugin, array $config = [])
    {
        $defaultConfig = [
            'enabled' => false,
            //'autoload' => false,
            'bootstrap' => true,
            'routes' => true,
            //'classBase' => 'src',
            'ignoreMissing' => true,
            'configs' => true,
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
            } catch (\Exception $ex) {}

            // create plugin class instance
            //if ($config['enabled'] === true) {
            /*
                $pluginClass = $plugin . '\\' . $plugin . 'Plugin';
                if (class_exists($pluginClass)) {
                    $pluginInst = new $pluginClass($config);

                    if ($pluginInst instanceof EventListenerInterface) {
                        EventManager::instance()->on($pluginInst);
                    }

                    if ($pluginInst instanceof PluginInterface) {
                        $pluginInst->registerEvents(EventManager::instance());
                    }

                    if (is_callable($pluginInst)) {
                        call_user_func($pluginInst, $config);
                    }
                }
            */
            //}


            // autoload local plugin configs
            if ($loadConfig === true) {
                static::_autoloadPluginConfig($plugin);
            }

        } catch (\Exception $ex) {
            $config += ['enabled' => false, 'error' => $ex->getMessage()];
            throw $ex; //@TODO Handle plugin loading exception
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