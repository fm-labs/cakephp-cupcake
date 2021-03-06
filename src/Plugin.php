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
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
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
            $panels['Cupcake.System'] = true;
            Configure::write('DebugKit.panels', $panels);
        }

        /*
         * Init Cupcake
         */
        Cupcake::init($app);

        /*
         * Add cupcake templates path as fallback template search path
         */
        $templatePaths = Configure::read('App.paths.templates', []);
        $templatePaths[] = \Cake\Core\Plugin::templatePath('Cupcake');
        Configure::write('App.paths.templates', $templatePaths);
    }
}
