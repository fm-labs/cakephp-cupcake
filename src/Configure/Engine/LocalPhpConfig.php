<?php

namespace Cupcake\Configure\Engine;

use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Exception\CakeException;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

class LocalPhpConfig extends PhpConfig
{
    protected $_read = [];

    public function read(string $key): array
    {
        if (isset($this->_read[$key])) {
            deprecationWarning("Configuration " . $key . " has already been loaded");
        }

        // read the original config
        $config = parent::read($key);

        // read app- or local overrides
        [$plugin, $key] = pluginSplit($key);
        if ($plugin && $key === Inflector::underscore($plugin)) {
            foreach (['plugin', 'local'] as $dir) {
                $filePath = $this->_path . $dir . DS . $key . '.php';
                //debug($filePath);
                if (file_exists($filePath)) {
                    $_config = $this->_readFile($filePath);
                    $config = Hash::merge($config, $_config);
                }
            }
        }

        $this->_read[$key] = true;

        return $config;
    }

    public function _readFile(string $file): array
    {
        $config = null;
        $return = include $file;
        if (is_array($return)) {
            return $return;
        }

        throw new CakeException(sprintf('Config file "%s" did not return an array', $file));
    }

}