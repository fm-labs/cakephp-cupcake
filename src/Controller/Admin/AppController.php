<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/29/15
 * Time: 6:00 PM
 */

namespace Banana\Controller\Admin;

use Backend\Controller\Admin\AbstractBackendController;
use Banana\Lib\Banana;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Filesystem\Folder;

class AppController extends AbstractBackendController
{
    public $viewClass = "Banana.Backend";

    public $paginate = [
        'limit' => 100,
    ];

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
        $mm = MediaManager::get('gallery');
        $list = $mm->getSelectListRecursive();
        return $list;
    }

    public static function backendMenu()
    {
        return [
            'Banana' => [
                'plugin' => 'Banana',
                'title' => 'Content',
                'url' => ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'index'],
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
                    /*
                    'media' => [
                        'title' => 'Media',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Media', 'action' => 'index'],
                        'icon' => ''
                    ],
                    */
                    'galleries' => [
                        'title' => 'Galleries',
                        'url' => ['plugin' => 'Banana', 'controller' => 'Galleries', 'action' => 'index'],
                        'icon' => ''
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

    /**
     * @deprecated
     */
    protected function getModulesAvailable()
    {
        return Banana::getModuleCellsAvailable();
    }

    /**
     * @deprecated
     */
    protected function getModuleTemplatesAvailable()
    {
        return Banana::getModuleCellTemplatesAvailable();
    }

    /**
     * @deprecated
     */
    protected function getLayoutsAvailable()
    {
        return Banana::getLayoutsAvailable();
    }

    /**
     * @deprecated
     */
    protected function getThemesAvailable()
    {
        return Banana::getLayoutsAvailable();
    }
}
