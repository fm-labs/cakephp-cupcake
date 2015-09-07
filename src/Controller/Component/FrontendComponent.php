<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 7/26/15
 * Time: 1:44 PM
 */

namespace Banana\Controller\Component;

use Cake\Core\Configure;
use Cake\Controller\Component;
use Cake\Event\Event;
use Banana\Model\Table\PagesTable;

/**
 * Class FrontendComponent
 * @package Banana\Controller\Component
 */
class FrontendComponent extends Component
{
    public static $pageModelClass = 'Banana.Pages';

    public static $pageThemeSetting = 'Settings.Banana.site.theme';

    public static $pageLayoutSetting = 'Settings.Banana.site.layout';

    /**
     * @var PagesTable
     */
    protected $Pages;

    protected $page;

    protected $layout;

    protected $theme;

    public function initialize(array $config)
    {
        $this->Pages = $this->_registry->getController()->loadModel(static::$pageModelClass);
    }

    public function beforeFilter(Event $event)
    {
    }

    public function beforeRender(Event $event)
    {
        $controller = $this->_registry->getController();
        if ($this->getTheme()) {
            $controller->theme = $this->getTheme();
        }
        if ($this->getLayout()) {
            $controller->layout = $this->getLayout();
        }
        //$controller->theme = ($controller->theme) ? $controller->theme : $this->getTheme();
        //$controller->layout = ($controller->layout) ? $controller->layout : $this->getLayout();
    }



    /**
     * Get the Page entity from database
     * If no Id or slug is given, check the request parameters
     * for 'pageid' and/or 'page' keys
     *
     * @param null $id Page Id
     * @param null $slug Page Slug
     * @return \Banana\Model\Entity\Page
     */
    public function getPage($id = null, $slug = null)
    {
        if (!$this->page || $id !== null || $slug !== null) {
            if (!$id && !$slug) {
                if ($this->request->param('pageid')) {
                    $id = $this->request->param('pageid');
                } elseif ($this->request->query('pageid')) {
                    $id = $this->request->query('pageid');
                } elseif ($slug !== null) {
                    // do nothing
                } elseif ($this->request->param('slug')) {
                    $slug = $this->request->param('slug');
                } elseif ($this->request->query('slug')) {
                    $slug = $this->request->query('slug');
                }
            }

            $page = null;
            if ($id) {
                $page = $this->Pages->get($id);
            } elseif ($slug) {
                $page = $this->Pages->find()->where(['slug' => $slug])->first();
            }
            $this->page = $page;
        }

        return $this->page;
    }

    public function getPageBySlug($slug)
    {
        return $this->getPage(null, $slug);
    }

    /**
     * Get Theme name from Page or fallback to config setting
     *
     * @param null $theme
     * @return string
     */
    public function getTheme($theme = null)
    {
        if (!$this->theme || $theme !== null) {
            if ($theme) {
                $this->theme = $theme;
            } elseif (($page = $this->getPage()) && $page->parent_theme) {
                // Get theme from Page
                $this->theme = $page->parent_theme;
            } else {
                // Fallback to configuration setting
                //$theme = Configure::read(static::$pageThemeSetting);
            }
        }
        return $this->theme;
    }

    public function getLayout($layout = null)
    {
        if (!$this->layout || $layout !== null) {
            if ($layout) {
                $this->layout = $layout;
            } elseif (($page = $this->getPage()) && $page->layout_template) {
                // Get theme from Page
                $this->layout = $page->layout_template;
            } else {
                // Fallback to configuration setting
                //$layout = Configure::read(static::$pageLayoutSetting);
            }
        }
        return $this->layout;
    }
} 