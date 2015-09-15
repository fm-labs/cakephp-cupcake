<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/5/15
 * Time: 10:43 PM
 */

namespace Banana\Lib;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class Banana
{

    public static $version;

    public static function version()
    {
        if (!isset(static::$version)) {
            static::$version = @file_get_contents(Plugin::path('Banana') . DS . 'VERSION.txt');
        }
        return static::$version;
    }

    public static function bootstrap()
    {
        $config = Configure::read('Banana');
        if ($config === null) {
            die("Banana Cake is not configured");
        }
    }

    public static function bootstrapConfigs()
    {
        if (Configure::check('Banana.configs')) {
            foreach ((array) Configure::read('Banana.configs') as $config) {
                Configure::load($config);
            }
        }
    }

    public static function bootstrapPlugins()
    {
        if (Configure::check('Banana.plugins')) {
            foreach ((array) Configure::read('Banana.plugins') as $plugin => $flags) {
                $flags = array_merge(['bootstrap' => false, 'routes' => false, 'config' => false], $flags);
                Plugin::load($plugin, $flags);

                if ($flags['config'] === true) {
                    $flags['config'] = Inflector::underscore($plugin);
                }
                if ($flags['config']) {
                    Configure::load($flags['config']);
                }
            }
        }
    }

    public static function getAvailablePageLayouts()
    {
        $PageLayouts = TableRegistry::get('Banana.PageLayouts');
        return $PageLayouts->find('list')->all();
    }

    public static function getAvailablePageTemplates()
    {
        return [];
    }

    public static function getAvailablePageTypes()
    {
        return [
            'content' => 'Content',
            'controller' => 'Controller',
            'cell' => 'Cell',
            'module' => 'Module',
            'page' => 'Page',
            'redirect' => 'Redirect',
            'root' => 'Website Root',
        ];
    }

    public static function getDefaultPageLayout()
    {
        $PageLayouts = TableRegistry::get('Banana.PageLayouts');
        $pageLayout = $PageLayouts->find('first')->where(['is_default' => true]);
        return $pageLayout;
    }
}
