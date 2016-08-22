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
    /**
     * @var Controller
     */
    public $controller;

    /**
     * @var PagesTable
     */
    public $Pages;

    protected $_defaultConfig = [
        'viewClass' => 'Banana.Content',
        'theme' => null,
        'layout' => null
    ];

    protected $_page;

    public function initialize(array $config)
    {
        $this->controller = $this->_registry->getController();

        if (is_null($this->_config['theme'])) {
            $this->_config['theme'] = Configure::read('Banana.Frontend.theme');
        }

        if (is_null($this->_config['layout'])) {
            $this->_config['layout'] = 'frontend';
        }

        $this->controller->loadComponent('Flash');
        $this->controller->viewBuilder()->className($this->_config['viewClass']);
        $this->controller->viewBuilder()->theme($this->_config['theme']);
        $this->controller->viewBuilder()->layout($this->_config['layout']);

    }

    public function setRefScope($scope)
    {
        $this->controller->set('refscope', $scope);
    }

    public function setRefId($id)
    {
        $this->controller->set('refid', $id);
    }

    public function setPageId($pageId)
    {
        $this->controller->set('page_id', $pageId);
        $this->setRefScope('Banana.Pages');
        $this->setRefId($pageId);
    }

    public function beforeFilter(Event $event)
    {
        //$this->detectPage();
    }

    public function beforeRender(Event $event)
    {

        //if (!$controller->theme && $this->_theme) {
        //    $controller->theme = $this->_theme;
        //}

        //if (!$controller->layout && $this->_layout) {
        //    $controller->layout = $this->_layout;
        //}
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

            $this->setPageId($pageId);
        }
    }

    /*
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
    */

}
