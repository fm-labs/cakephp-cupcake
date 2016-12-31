<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/30/16
 * Time: 11:01 PM
 */

namespace Banana\Lib;

use Banana\Exception\InvalidPluginManifestException;
use Banana\Exception\MissingPluginConfigException;
use Banana\Exception\MissingPluginManifestException;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
use DirectoryIterator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class BananaPlugin extends Plugin
{

    /**
     * @param null $plugin
     * @param null $path
     * @return array
     */
    static public function install($plugin = null, $path = null)
    {
        if (!$plugin) {
            throw new \InvalidArgumentException('Unable to install banana plugin: Missing plugin name');
        }

        // find plugin directory
        if (!$path) {

            static::_loadConfig();
            foreach (App::path('Plugin') as $pluginDir) {
                if (!is_dir($pluginDir)) {
                    continue;
                }
                $dir = new DirectoryIterator($pluginDir);
                foreach ($dir as $_dir) {
                    if ($_dir->isDir() && !$_dir->isDot() && $_dir->getBasename() == $plugin) {
                        $path = $_dir->getPath() . DS . $_dir->getBasename();
                        break;
                    }
                }

                if ($path) {
                    break;
                }
            }
        }
        
        if (!$path) {
            throw new MissingPluginException(['plugin' => $plugin]);
        }

        // read the plugin manifest
        $manifestFilepath = $path . DS . 'config' . DS . 'banana.php';
        $confKey = 'Banana.Plugin.' . $plugin;
        $config = [];
        try {
            // create plugin config from plugin manifest
            $manifest = static::_loadPluginManifest($plugin, $manifestFilepath);
            if (!isset($manifest[$confKey])) {
                throw new InvalidPluginManifestException(['plugin' => $plugin]);
            }

            $config = $manifest[$confKey];
            $config['enabled'] = true;
            $config['manifest'] = '1.0';

            static::_dumpPluginConfig($plugin, [$confKey => $config]);

        } catch(MissingPluginManifestException $ex) {

            $config = [
                'title' => $plugin,
                'bootstrap' => false,
                'routes' => false,
                'enabled' => true, //@TODO evaluate auto-enabling user plugins without manifests
                'manifest' => false,
            ];
            static::_dumpPluginConfig($plugin, [$confKey => $config]);

        } catch (\Exception $ex) {
            $config = [
                'error' => $ex->getMessage(),
                'enabled' => false,
            ];
        }

        $config['_installDate'] = date(DATE_RSS);
        $config['_installTime'] = time();
        return $config;
    }

    /**
     * @param array $options
     * @throws \Exception
     */
    static public function loadAll(array $options = [])
    {
        parent::loadAll(['bootstrap' => false, 'routes' => false, 'autoload' => false]);

        $path = CONFIG . 'plugins' . DS;
        if (!is_dir($path)) {
            return;
        }

        $files = new DirectoryIterator($path);
        $files_array = array();

        while($files->valid()) {
            // sort key, ie. modified timestamp
            if (!$files->isDot() && $files->getExtension() == 'json') {
                $key = Inflector::camelize(substr($files->getFilename(), 0, -5));
                $data = $files->getPath() . DS . $files->getFilename();
                $files_array[$key] = $data;
            }
            $files->next();
        }

        //ksort($files_array);
        foreach ($files_array as $pluginName => $pluginConfigFilepath) {
            static::loadUserPlugin($pluginName);
        }
    }

    /**
     * @param array|string $plugin
     * @param array $config
     * @throws \Exception
     * @return void
     */
    public static function loadUserPlugin($plugin, array $config = [])
    {
        $defaultConfig = [
            'bootstrap' => false,
            'routes' => false,
            'enabled' => false,
        ];

        $confKey = 'Banana.Plugin.' . $plugin;
        try {
            // read config
            $pluginConfig = static::_loadPluginConfig($plugin);
            if (!isset($pluginConfig[$confKey])) {
                throw new InvalidPluginManifestException(['plugin' => $plugin]);
            }
            $config = array_merge($defaultConfig, $pluginConfig[$confKey], $config);

            if ($config['enabled'] === true) {
                parent::load($plugin, $config);
            }
        //} catch (MissingPluginConfigException $ex) {

        } catch (\Exception $ex) {
            $config = ['enabled' => false, 'error' => $ex->getMessage()];
        }

        Configure::write($confKey, $config);
    }

    /**
     * @param $pluginName
     * @return mixed
     */
    static protected function _loadPluginConfig($pluginName)
    {
        $path = CONFIG . 'plugins' . DS . Inflector::underscore($pluginName) . '.json';
        $config = static::_readJsonConfig($path);
        if (!$config) {
            throw new MissingPluginConfigException(['plugin' => $pluginName]);
        }
        return $config;
    }

    /**
     * @param $plugin
     * @param $config
     * @return int
     */
    static protected function _dumpPluginConfig($plugin, $config)
    {

        $path = CONFIG . 'plugins' . DS . Inflector::underscore($plugin) . '.json';
        return static::_writeJsonConfig($path, $config);
    }

    /**
     * @param $pluginName
     * @param null $path
     * @return mixed
     */
    static protected function _loadPluginManifest($pluginName, $path = null)
    {
        if ($path === null) {
            $path = App::path('config', $pluginName) . 'banana.php';
        }
        $manifest = static::_readPhpConfig($path);
        if (!$manifest) {
            throw new MissingPluginManifestException(['plugin' => $pluginName]);
        }
        return $manifest;
    }


    /**
     * @param $path
     * @return mixed
     */
    static protected function _readJsonConfig($path)
    {
        $loader = function() use ($path) {
            if (!file_exists($path)) {
                return false;
            }

            $config = file_get_contents($path);
            return json_decode($config, true);
        };
        return $loader();
    }

    /**
     * @param $path
     * @param array $data
     * @return int
     */
    static protected function _writeJsonConfig($path, array $data)
    {
        return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
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