<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/17/15
 * Time: 6:34 PM
 */

namespace Banana\Controller;

use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Banana\Model\Table\PagesTable;
use Cake\Core\Configure;
use Cake\View\Exception\MissingTemplateException;
use Banana\Controller\Component\FrontendComponent;

/**
 * Class FrontendController
 * @package App\Controller
 *
 * @property FrontendComponent $Frontend
 * @property PagesTable $Pages
 */
class PagesController extends FrontendController
{

    public $modelClass = 'Banana.Pages';

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    public function index()
    {
        $rootPage = $this->Pages
            ->find()
            ->where(['parent_id IS NULL'])
            ->first();

        if (!$rootPage) {
            throw new NotFoundException("No root page");
        }

        $this->setAction('view', $rootPage->id);
    }

    public function view($id = null)
    {
        $page = $this->Frontend->getPage($id);
        if (!$page) {
            throw new NotFoundException();
        }

        switch ($page->type) {
            case 'redirect':
                return $this->redirect($page->redirect_location, $page->redirect_status);
            case 'page':
                return $this->redirect(['action' => 'view', $page->redirect_page_id], $page->redirect_status);
            case 'controller':
                $controller = explode(':', $page->redirect_controller);
                $action = 'index';
                if (count($controller) == 2) {
                    list($controller, $action) = $controller;
                } elseif (count($controller) == 1) {
                    $controller = $controller[0];
                } else {
                    throw new Exception("Malformed controller route");
                }

                list($plugin, $controller) = pluginSplit($controller);
                $url = ['plugin' => $plugin, 'controller' => $controller, 'action' => $action];
                return $this->redirect($url, $page->redirect_status);
            case 'content':
            default:
                break;
        }

        $this->set('title', $page->title);
        $this->set('page', $page);
        $this->set('pageId', $page->id);
        $this->request->params['page_id'] = $page->id;
    }

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display()
    {
        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

}
