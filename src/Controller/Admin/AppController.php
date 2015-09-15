<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/29/15
 * Time: 6:00 PM
 */

namespace Banana\Controller\Admin;

use Backend\Controller\Admin\AbstractBackendController;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Filesystem\Folder;

class AppController extends AbstractBackendController
{
    public $viewClass = "Banana.Banana";

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $locale = $this->request->query('locale');
        $this->locale = ($locale) ? $locale : Configure::read('Shop.defaultLocale');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('locale', $this->locale);
    }

    protected function _getGalleryList()
    {
        $list = [];
        $mm = MediaManager::create('gallery');
        $list = $mm->getSelectListRecursive();
        return $list;
    }

    public static function backendMenu()
    {
        return [
            'Banana' => [
                'plugin' => 'Banana',
                'title' => 'Content',
                'url' => ['plugin' => 'Banana', 'controller' => 'ContentManager', 'action' => 'index'],
                'icon' => 'desktop',

                '_children' => [
                    /*
                    'content_manager' => [
                        'title' => 'Content Manager',
                        'url' => ['plugin' => 'Banana', 'controller' => 'ContentManager', 'action' => 'index'],
                        'icon' => 'content'
                    ],
                    */
                    'pages' => [
                        'title' => 'Pages',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'index'],
                        'icon' => 'sitemap'
                    ],
                    'posts' => [
                        'title' => 'Posts',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'index'],
                        'icon' => 'edit'
                    ],
                    'media' => [
                        'title' => 'Media',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Media', 'action' => 'index'],
                        'icon' => 'media'
                    ],
                ]
            ],
            'BananaAdvanced' => [
                'plugin' => 'Banana',
                'title' => 'Advanced',
                'url' => ['plugin' => 'Banana', 'controller' => 'ContentManager', 'action' => 'index'],
                'icon' => 'configure',

                '_children' => [
                    'page_layouts' => [
                        'title' => 'Layouts',
                        'url' => ['plugin' => 'Banana', 'controller' => 'PageLayouts', 'action' => 'index'],
                        'icon' => 'file'
                    ],
                    'module_builder' => [
                        'title' => 'Module Builder',
                        'url' => ['plugin' => 'Banana', 'controller' => 'ModuleBuilder', 'action' => 'index'],
                        'icon' => 'wizard'
                    ],
                    'modules' => [
                        'title' => 'Modules',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Modules', 'action' => 'index'],
                        'icon' => 'block layout'
                    ],
                    'content_modules' => [
                        'title' => 'Content Modules',
                        'url' => ['plugin' => 'Banana', 'controller' => 'ContentModules', 'action' => 'index'],
                        'icon' => 'content'
                    ],
                    'settings' => [
                        'title' => 'Settings',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Settings', 'action' => 'index'],
                        'icon' => 'settings'
                    ],
                    'users' => [
                        'title' => 'Users',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Users', 'action' => 'index'],
                        'icon' => 'users'
                    ],
                    'themes_manager' => [
                        'title' => 'Theme',
                        'url' => ['plugin' => 'Banana', 'controller' => 'ThemesManager', 'action' => 'index'],
                        'icon' => 'paint brush'
                    ],
                ]
            ]
        ];
    }


    protected function getModulesAvailable()
    {
        $path = 'View' . DS . 'Module';
        $availableModules = [];

        $modulesLoader = function ($dir, $plugin = null) use (&$availableModules) {
            $folder = new Folder($dir);
            list($namespaces,) = $folder->read();

            foreach ($namespaces as $ns) {
                $folder->cd($dir . DS . $ns);
                $widgets = $folder->findRecursive();
                array_walk($widgets, function ($val) use ($plugin, $dir, &$availableModules) {
                    $val = substr($val, strlen($dir . DS));
                    if (preg_match('/^(.*)Module\.php$/', $val, $matches)) {
                        $availableModules[] = ($plugin) ? $plugin . "." . $matches[1] : $matches[1];
                    }
                });
            }
        };

        // load app modules
        $modulesLoader(APP . $path, null);
        // load modules from loaded plugins
        foreach (Plugin::loaded() as $plugin) {
            $_path = Plugin::path($plugin) . 'src' . DS . $path;
            $modulesLoader($_path, $plugin);
        }

        return $availableModules;
    }

    protected function getModuleTemplatesAvailable()
    {
        $path = 'Template' . DS . 'Module';
        $availableModules = [];

        $modulesLoader = function ($dir, $plugin = null) use (&$availableModules) {
            $folder = new Folder($dir);
            list($namespaces,) = $folder->read();

            foreach ($namespaces as $ns) {
                $folder->cd($dir . DS . $ns);
                $widgets = $folder->findRecursive();
                array_walk($widgets, function ($val) use ($plugin, $dir, &$availableModules) {
                    $val = substr($val, strlen($dir . DS));
                    if (preg_match('/^(.*)\.ctp$/', $val, $matches)) {
                        $availableModules[] = ($plugin) ? $plugin . "." . $matches[1] : $matches[1];
                    }
                });
            }
        };

        // load app modules
        $modulesLoader(APP . $path, null);
        // load modules from loaded plugins
        foreach (Plugin::loaded() as $plugin) {
            $_path = Plugin::path($plugin) . 'src' . DS . $path;
            $modulesLoader($_path, $plugin);
        }

        return array_combine($availableModules, $availableModules);
    }

    protected function getLayoutsAvailable()
    {
        $path = 'Template' . DS . 'Layout';
        $availableLayouts = [];

        $layoutLoader = function ($dir, $plugin = null) use (&$availableLayouts) {
            $folder = new Folder($dir);
            list(,$layouts) = $folder->read();
            array_walk($layouts, function ($val) use ($plugin, $dir, &$availableLayouts) {
                //$val = substr($val, strlen($dir . DS));
                $val = basename($val, '.ctp');
                if (preg_match('/^frontend(\_(.*))?$/', $val, $matches)) {

                    $availableLayouts[] = ($plugin) ? $plugin . "." . $val : $val;
                }
            });


            /*
            list($namespaces,) = $folder->read();

            foreach ($namespaces as $ns) {
                $folder->cd($dir . DS . $ns);
                $layouts = $folder->find();
                debug($layouts);
                array_walk($layouts, function ($val) use ($plugin, $dir, &$availableLayouts) {
                    //$val = substr($val, strlen($dir . DS));
                    if (preg_match('/^(.*)\.ctp$/', $val, $matches)) {
                        $availableLayouts[] = ($plugin) ? $plugin . "." . $matches[1] : $matches[1];
                    }
                });
            }
            */
        };

        // load app modules
        $layoutLoader(APP . $path, null);
        // load modules from loaded plugins
        foreach (Plugin::loaded() as $plugin) {
            $_path = Plugin::path($plugin) . 'src' . DS . $path;
            $layoutLoader($_path, $plugin);
        }

        $availableLayouts = array_combine($availableLayouts, $availableLayouts);
        return $availableLayouts;
    }

    protected  function getThemesAvailable()
    {
        $availableThemes = [];

        $themesLoader = function ($dir, $plugin = null) use (&$availableThemes) {
            $folder = new Folder($dir);
            list($themes,) = $folder->read();
            array_walk($themes, function ($val) use ($plugin, $dir, &$availableThemes) {
                //$val = substr($val, strlen($dir . DS));
                //$val = basename($val, '.ctp');
                if (preg_match('/^Theme(.*)$/', $val, $matches)) {
                    $availableThemes[] = ($plugin) ? $plugin . "." . $val : $val;
                }
            });
        };

        // load app modules
        $themesLoader(THEMES, null);
        // load modules from loaded plugins
        //foreach (Plugin::loaded() as $plugin) {
        //    $_path = Plugin::path($plugin) . 'src' . DS . $path;
        //    $themesLoader($_path, $plugin);
        //}

        $availableThemes = array_combine($availableThemes, $availableThemes);
        return $availableThemes;
    }
}
