<?php
namespace Banana\Configure\Engine;

use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Database\Exception as DatabaseException;
use Cake\Log\Log;

/**
 * Class SettingsConfig
 *
 * @package Settings\Configure\Engine
 */
class SettingsConfig extends PhpConfig
{
    /**
     * @var string Path to settings dir
     */
    protected $_path;

    /**
     * File extension.
     *
     * @var string
     */
    protected $_extension = '.php';

    /**
     * @param string|null $configPath Path to config dir. Defaults to ROOT/config.
     */
    public function __construct($configPath = null)
    {
        if ($configPath === null && defined('SETTINGS')) {
            $configPath = constant('SETTINGS');
        }
        parent::__construct($configPath);
    }


    /**
     * Read Settings configuration
     *
     * Load settings config file of given scope
     *
     * @param string $key Settings scope
     * @return array|mixed
     * @throws \Cake\Core\Exception\Exception
     */
    public function read($key)
    {
        $file = $this->_buildSettingsFilePath($key);

        if (!is_file($file)) {
            Log::warning("SettingsConfig: File $file not found");
            return [];
        }

        $config = include $file;
        if (!is_array($config)) {
            throw new Exception(sprintf('Settings config "%s" did not return an array', $file));
        }

        return $config;
    }

    /**
     * Converts the provided $data into a string of PHP code that can
     * be used saved into a file and loaded later.
     *
     * @param string $key The identifier to write to. If the key has a . it will be treated
     *  as a plugin prefix.
     * @param array $data Data to dump.
     * @return int Bytes saved.
     */
    public function dump($key, array $data)
    {
        $contents = '<?php' . "\n" . 'return ' . var_export($data, true) . ';' . "\n";

        $filename = $this->_buildSettingsFilePath($key);
        return file_put_contents($filename, $contents);
    }

    /**
     * Converts the provided $data into a string of JSON that can
     * be saved into a file and loaded later.
     *
     * @param string $key The identifier to write to. If the key has a . it will be treated
     *  as a plugin prefix.
     * @param array $data Data to dump.
     * @return int Bytes saved.
     */
    public function dumpSchema($key, array $data)
    {
        $contents = json_encode($data, JSON_PRETTY_PRINT);

        $filename = $this->_buildSettingsSchemaFilePath($key);
        return file_put_contents($filename, $contents);
    }

    /**
     * Return path to settings file
     *
     * @param string $key Settings key
     * @return string
     */
    protected function _buildSettingsSchemaFilePath($key)
    {
        return self::buildSettingsFilePath($this->_path, $key, 'schema.json');
    }

    /**
     * Return path to settings file
     *
     * @param string $key Settings key
     * @return string
     */
    protected function _buildSettingsFilePath($key)
    {
        return self::buildSettingsFilePath($this->_path, $key, $this->_extension);
    }

    /**
     * @param string $settingsPath Path to settings
     * @param string $key Settings scope
     * @param string $ext File extension
     * @return string Path to file
     * @throws \Cake\Core\Exception\Exception
     */
    public static function buildSettingsFilePath($settingsPath, $key, $ext = '.php')
    {
        if (strpos($key, '..') !== false) {
            throw new Exception('Cannot load/dump settings schema with ../ in them.');
        }

        return $settingsPath . 'settings.' . $key . $ext;
    }

    public static function buildSettingsSchemaPath($settingsPath, $ext = '.php')
    {
        if (strpos($settingsPath, '..') !== false) {
            throw new Exception('Cannot load/dump settings schema with ../ in them.');
        }

        return $settingsPath . 'settings' . $ext;
    }

    /**
     * @param $settingsPath
     * @return mixed
     * @throws \Exception
     */
    public static function readSchema($settingsPath)
    {

        $settingsFile = static::buildSettingsSchemaPath($settingsPath);

        if (!is_file($settingsFile)) {
            throw new \Exception("Cannot read settings schema from " . $settingsFile);
        }


        $loader = function() use ($settingsFile) {
            return include $settingsFile;
        };
        $schema = $loader();

        if (!is_array($schema) || !isset($schema['Settings'])) {
            throw new Exception(sprintf('Settings file "%s" has no Settings defined', $settingsFile));
        }

        return $schema['Settings'];
    }
}
