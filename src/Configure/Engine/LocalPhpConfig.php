<?php
declare(strict_types=1);

namespace Cupcake\Configure\Engine;

use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Exception\CakeException;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

class LocalPhpConfig extends PhpConfig
{
    protected $_read = [];

    /**
     * @inheritDoc
     */
    public function read(string $key): array
    {
        if (isset($this->_read[$key])) {
            deprecationWarning('Configuration ' . $key . ' has already been loaded');
        }

        // read the original config
        $config = parent::read($key);

        // read app- or local overrides
        [$plugin, $key] = pluginSplit($key);
        if ($plugin && $key === Inflector::underscore($plugin)) {
            foreach (['plugins', 'local'] as $dir) {
                $filePath = $this->_path . $dir . DS . $key . '.php';
                //debug("Load local config $filePath for key $key");
                if (file_exists($filePath)) {
                    $_config = $this->_readFile($filePath);
                    $config = Hash::merge($config, $_config);
                }
            }
        } elseif (!$plugin && substr($key, 0, 5) !== 'local') {
            foreach (['local'] as $dir) {
                $filePath = $this->_path . $dir . DS . $key . '.php';
                //debug("Load local config $filePath for key $key");
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
