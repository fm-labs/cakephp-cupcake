<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Cache\Cache;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;

class Plugin extends BasePlugin
{
    /**
     * @var bool
     */
    public $routesEnabled = false;

    /**
     * @var bool
     */
    public $bootstrapEnabled = true;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $app->addOptionalPlugin('Settings');

        defined('DATA_DIR') || define('DATA_DIR', ROOT . DS . 'data' . DS);

        /**
         * Cache config
         */
        if (!Cache::getConfig('cupcake')) {
            Cache::setConfig('cupcake', [
                'className' => 'File',
                'duration' => '+1 hours',
                'path' => CACHE,
                'prefix' => 'cupcake_core_',
            ]);
        }

        /**
         * DebugKit
         */
        if (\Cake\Core\Plugin::isLoaded('DebugKit')) {
            $panels = Configure::read('DebugKit.panels', []);
            //print_r($panels);
            $panels['Cupcake.System'] = true;
            Configure::write('DebugKit.panels', $panels);
        }


        /**
         * Load default content config
         */
        if (\Cake\Core\Plugin::isLoaded('Settings')) {
            Configure::load('Cupcake', 'settings');
        }
    }
}
