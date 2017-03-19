<?php

namespace Banana\Plugin;

use Banana\Exception\InvalidPluginManifestException;
use Banana\Exception\MissingPluginConfigException;
use Banana\Exception\MissingPluginManifestException;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Filesystem\Folder;
use Cake\Utility\Inflector;
use DirectoryIterator;

class PluginLoader extends Plugin
{

    /**
     * @return void
     */
    static protected function _loadPluginsConfig()
    {
        if (Configure::check('Banana.plugins')) {
            return;
        }

        static::_loadConfig();

        $plugins = [];


        if (!Configure::check('Plugin')) {
            // read plugins from plugins.php config
            try {
                Configure::load('plugins');
            } catch(\Exception $ex) {}
        }

        $plugins = (array) Configure::consume('Plugin');

        /*
        $localPluginsConfigPath = CONFIG . 'plugins' . DS;
        $dir = new DirectoryIterator($localPluginsConfigPath);
        foreach ($dir as $_dir) {
            if ($_dir->isFile() && !$_dir->isDot()) {
                $plugin = $_dir->getBasename();
                $pluginConfig = self::_readPhpConfig($_dir->getPathname());
                $plugins[$plugin] = $pluginConfig;
            }
        }
        */

        $defaultConfig = ['enabled' => false, 'bootstrap' => true, 'routes' => true];
        foreach ($plugins as $plugin => &$pluginConfig) {
            if (is_bool($pluginConfig)) { // Boolean value maps to enabled state
                $pluginConfig = array_merge($defaultConfig, ['enabled' => $pluginConfig]);
            } elseif (is_array($pluginConfig)) {
                $pluginConfig = array_merge($defaultConfig, $pluginConfig);
            } else {
                //@TODO Throw exception indicating an invalid plugin configuration
                $pluginConfig = $defaultConfig;
                throw new \InvalidArgumentException(sprintf("Invalid plugin config data type given for plugin '%s'", $plugin));
            }
        }

        Configure::write('Banana.plugins',  $plugins);
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

        $localPluginConfigFile = CONFIG . DS . 'plugins.php';
        $config = ['Plugin' => Configure::read('Banana.plugins')];
        self::_writePhpConfig($localPluginConfigFile, $config);
    }

    /**
     * Load all configured plugins
     *
     * @param array $options
     * @throws \Exception
     */
    static public function loadAll(array $options = [])
    {
        self::_loadPluginsConfig();
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
            'ignoreMissing' => true
        ];
        $config = array_merge($defaultConfig, $config);

        // disable routes for disabled plugins
        if ($config['enabled'] !== true) {
            return;
        }

        try {
            parent::load($plugin, $config);

            // create plugin class instance
            //if ($config['enabled'] === true) {
                $pluginClass = $plugin . '\\' . $plugin . 'Plugin';
                if (class_exists($pluginClass)) {
                    $pluginInst = new $pluginClass();

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
            //}


            // autoload local plugin configs
            //if ($config['config'] === true) {
                $configFiles = [
                    'plugins/' . $plugin . '/' . Inflector::underscore($plugin), // from local plugins config folder
                    'local/' . Inflector::underscore($plugin) // from local config folder
                ];
                foreach ($configFiles as $configFile) {
                    if (file_exists(CONFIG . $configFile . '.php')) {
                        Configure::load($configFile);
                    }
                }
            //}

        } catch (\Exception $ex) {
            $config += ['enabled' => false, 'error' => $ex->getMessage()];
            throw $ex; //@TODO Handle plugin loading exception
        }


        Configure::write('Plugin.' . $plugin, $config);
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