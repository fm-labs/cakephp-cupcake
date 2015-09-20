<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 7/26/15
 * Time: 1:44 PM
 */

namespace Banana\Controller\Component;

use Banana\Model\Entity\Page;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Controller\Component;
use Cake\Event\Event;
use Banana\Model\Table\PagesTable;
use Cake\I18n\I18n;

/**
 * Class FrontendComponent
 * @package Banana\Controller\Component
 */
class FrontendComponent extends Component
{
    public static $defaultViewClass = 'Banana.Frontend';

    public static $pageModelClass = 'Banana.Pages';

    //public static $pageThemeSetting = 'Settings.Banana.site.theme';

    //public static $pageLayoutSetting = 'Settings.Banana.site.layout';

    /**
     * @var Controller
     */
    public $controller;

    /**
     * @var PagesTable
     */
    public $Pages;

    protected $_page;

    protected $_theme;

    protected $_layout;

    public function initialize(array $config)
    {
        $this->controller = $this->_registry->getController();

        $this->Pages = $this->controller->loadModel(static::$pageModelClass);

        if (!$this->controller->viewClass) {
            $this->controller->viewClass = static::$defaultViewClass;
        }
        //if (!$controller->theme) {
        //    $controller->theme = null;
        //}
        if (!$this->controller->layout) {
            $this->controller->layout = 'frontend';
        }

    }

    public function beforeFilter(Event $event)
    {

    }

    public function beforeRender(Event $event)
    {
        $controller = $this->_registry->getController();

        if (!isset($controller->helpers['Banana.Script'])) {
            $controller->helpers['Banana.Script'] = [];
        }

        $this->detectPage();

        if (!$controller->theme && $this->_theme) {
            $controller->theme = $this->_theme;
        }
        if (!$controller->layout && $this->_layout) {
            $controller->layout = $this->_layout;
        }
    }

    public function detectPage()
    {
        if (!$this->_page) {
            $pageId = null;
            if ($this->request->param('page_id')) {
                $pageId = $this->request->param('page_id');
            }
            elseif ($this->request->query('page_id')) {
                $pageId = $this->request->query('page_id');
            }
            if ($pageId) {
                $page = $this->Pages->get($pageId);
                $this->setPage($page);
            }
        }
    }

    public function setPage(Page $page)
    {
        $this->_page = $page;

        $pageLayout = $this->Pages->getPageLayoutFor($page->id);
        if ($pageLayout) {
            $this->_theme = $pageLayout->theme;
            $this->_layout = $pageLayout->layout;
        }

        $this->controller->set('page', $page);
        $this->controller->set('pageId', $page->id);
        $this->controller->set('pageTitle', $page->title);

        $this->request->params['page_id'] = $page->id;
    }

}
