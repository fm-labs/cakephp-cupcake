<?php
namespace Banana\Core;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
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

    public static function routes()
    {
        // @todo Implement me
    }

    public static function getAvailablePageLayouts()
    {
        $PageLayouts = TableRegistry::get('Banana.PageLayouts');
        return $PageLayouts->find('list')->all();
    }


    public static function getDefaultPageLayout()
    {
        $PageLayouts = TableRegistry::get('Banana.PageLayouts');
        $pageLayout = $PageLayouts->find('first')->where(['is_default' => true]);
        return $pageLayout;
    }

    public static function getModulesAvailable()
    {
        $modules =  [
            'PostsList' => [
                'class' => 'Banana.PostsList'
            ],
            'PostsView' => [
                'class' => 'Banana.PostsView'
            ],
            'TextHtml' => [
                'class' => 'Banana.TextHtml'
            ],
            'Flexslider' => [
                'class' => 'Banana.Flexslider'
            ],
            'PagesMenu' => [
                'class' => 'Banana.PagesMenu'
            ],
            'PagesSubmenu' => [
                'class' => 'Banana.PagesSubmenu'
            ],
            'Image' => [
                'class' => 'Banana.Image'
            ]
        ];

        if (Configure::check('Banana.modules')) {
            $modules = array_merge($modules, Configure::read('Banana.modules'));
        }

        return $modules;
    }


    /**
     * @return array
     * @todo Refactor for module elements instead of module cells
     */
    public static function getModuleCellsAvailable()
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

    public static function getModuleCellTemplatesAvailable()
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

    public static function getLayoutsAvailable()
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

    public static function getThemesAvailable()
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

    public static function getContentSections()
    {
        return [
            'before' => 'before',
            'after' => 'after',
            'main' => 'main',
            'top' => 'top',
            'bottom' => 'bottom',
        ];
    }


    /**
     * @deprecated Use getContentSections() instead
     */
    public static function listContentSections()
    {
        return self::getContentSections();
    }


    public static function getAvailablePageTemplates()
    {
        $path = 'Template' . DS . 'Pages';
        $available = [];

        $modulesLoader = function ($dir, $plugin = null) {
            $list = [];
            $folder = new Folder($dir);
            list(,$files) = $folder->read();

            array_walk($files, function ($val) use ($plugin, $dir, &$list) {
                if (preg_match('/^\_/', $val)) {
                    return;
                }
                elseif (preg_match('/^([\w\_]+)\.ctp$/', $val, $matches)) {
                    $name = $matches[1];
                    //$template = ($plugin) ? $plugin . "." . 'teaser_' . $matches[1] : 'teaser_' . $matches[1];
                    $template = $matches[1];
                    $list[$template] = $name;
                }
            });

            return $list;
        };

        // load app modules
        $available['App'] = $modulesLoader(APP . $path, null);

        // load modules from loaded plugins
        foreach (Plugin::loaded() as $plugin) {
            $_path = Plugin::path($plugin) . 'src' . DS . $path;
            $templates = $modulesLoader($_path, $plugin);
            if ($templates) {
                $available[$plugin] = $templates;
            }
        }

        return $available;
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
            'static' => 'Static',
            'shop_category' => 'ShopCategory',
            'shop_product' => 'ShopProduct'
        ];
    }

    public static function getAvailablePostTeaserTemplates()
    {
        $path = 'Template' . DS . 'Posts';
        $available = [];

        $modulesLoader = function ($dir, $plugin = null) {
            $list = [];
            $folder = new Folder($dir);
            list(,$files) = $folder->read();

            array_walk($files, function ($val) use ($plugin, $dir, &$list) {
                if (preg_match('/^teaser_([\w\_]+)\.ctp$/', $val, $matches)) {
                    $name = $matches[1];
                    //$template = ($plugin) ? $plugin . "." . 'teaser_' . $matches[1] : 'teaser_' . $matches[1];
                    $template = 'teaser_' . $matches[1];
                    $list[$template] = $name;
                }
            });

            return $list;
        };

        // load app modules
        $available['App'] = $modulesLoader(APP . $path, null);

        // load modules from loaded plugins
        foreach (Plugin::loaded() as $plugin) {
            $_path = Plugin::path($plugin) . 'src' . DS . $path;
            $templates = $modulesLoader($_path, $plugin);
            if ($templates) {
                $available[$plugin] = $templates;
            }
        }

        return $available;
    }

    public static function getAvailablePostTemplates()
    {
        $path = 'Template' . DS . 'Posts';
        $available = [];

        $modulesLoader = function ($dir, $plugin = null) {
            $list = [];
            $folder = new Folder($dir);
            list(,$files) = $folder->read();

            array_walk($files, function ($val) use ($plugin, $dir, &$list) {
                if (preg_match('/^\_/', $val)) {
                    return;
                }
                elseif (preg_match('/^([\w\_]+)\.ctp$/', $val, $matches)) {
                    $name = $matches[1];
                    //$template = ($plugin) ? $plugin . "." . 'teaser_' . $matches[1] : 'teaser_' . $matches[1];
                    $template = $matches[1];
                    $list[$template] = $name;
                }
            });

            return $list;
        };

        // load app modules
        $available['App'] = $modulesLoader(APP . $path, null);

        // load modules from loaded plugins
        foreach (Plugin::loaded() as $plugin) {
            $_path = Plugin::path($plugin) . 'src' . DS . $path;
            $templates = $modulesLoader($_path, $plugin);
            if ($templates) {
                $available[$plugin] = $templates;
            }
        }

        return $available;
    }


}