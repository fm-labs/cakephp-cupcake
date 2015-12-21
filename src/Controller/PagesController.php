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
use Cake\Network\Response;
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

    public $viewClass = 'Banana.Frontend';

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->autoRender = false;
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    public function index()
    {
        $host = env('HTTP_HOST');

        $rootPage = $this->Pages
            ->find()
            ->find('published')
            ->where(['parent_id IS NULL', 'type' => 'root', 'title' => $host])
            ->first();

        if (!$rootPage) {
            $rootPage = $this->Pages
                ->find()
                ->find('published')
                ->where(['parent_id IS NULL', 'type' => 'root'])
                ->first();
        }

        if (!$rootPage) {
            throw new NotFoundException(__d('banana',"Root page missing for host {0}", $host));
        }

        $this->setAction('view', $rootPage->id);
    }

    /**
     * @param null $id
     * @return \Cake\Network\Response|void
     *
     * @deprecated Use 'display' method instead
     */
    public function view($id = null)
    {
        if ($id === null) {
            switch (true) {
                case isset($this->request->query['page_id']):
                    $id = $this->request->query['page_id'];
                    break;
                case isset($this->request->params['slug']):
                    $page = $this->Pages
                        ->find()
                        ->find('published')
                        ->where(['slug' => $this->request->params['slug']])
                        ->contain(['Posts'])
                        ->first();
                    break;
                default:
                    //throw new NotFoundException();
            }
        }

        if (!isset($page)) {
            $page = $this->Pages
                ->find('published')
                ->where(['Pages.id' => $id])
                ->contain(['Posts'])
                ->first();
        }

        if (!$page) {
            throw new NotFoundException(__d('banana',"Page not found"));
        }

        $this->Frontend->setRefId($page->id);

        $view = ($page->page_template) ?: null;
        $layout = ($page->page_layout) ? $page->page_layout->template : null;

        $this->viewBuilder()->template($view);
        $this->viewBuilder()->layout($layout);

        switch ($page->type) {
            case 'redirect':
                return $this->redirect($page->redirect_location, $page->redirect_status);
            case 'page':
            case 'root':
                $page = $this->Pages->get($page->redirect_page_id, ['contain' => []]);
                return $this->redirect($page->url, $page->redirect_status);
            case 'controller':
                return $this->redirect($page->redirect_controller_url, $page->redirect_status);
            //case 'root':
                //$children = $this->Pages->find('children', ['for' => $page->id]);
                //debug($children);
                break;

            case 'cell':
                $cellName = $page->redirect_controller;
                $this->setAction('cell', $cellName);
                //$this->Frontend->setPage($page);
                break;

            case 'module':
                $moduleName = $page->redirect_controller;
                $this->setAction('module', $moduleName);
                //$this->Frontend->setPage($page);
                break;

            case 'static':
                $action = ($page->page_template) ?: null;
                if ($action && method_exists($this, $action)) {
                    $this->setAction($action);
                    break;
                }
            case 'content':
            default:
                //$this->Frontend->setPage($page);

                break;
        }

        $contentModules = $this->Pages->ContentModules
            ->find()
            ->order(['ContentModules.priority' => 'DESC'])
            ->where(['ContentModules.refid' => $page->id, 'ContentModules.refscope' => 'Banana.Pages'])
            ->contain(['Modules'])
            ->all();

        $this->set('page', $page);
        $this->set('contentModules', $contentModules);


        return $this->render();
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
        $this->set(compact('path', 'page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }


    /**
     * Instantiates the correct view class, hands it its data, and uses it to render the view output.
     *
     * @param string $view View to use for rendering
     * @param string $layout Layout to use
     * @return \Cake\Network\Response A response object containing the rendered view.
     * @link http://book.cakephp.org/3.0/en/controllers.html#rendering-a-view
     */
    public function render($view = null, $layout = null)
    {
        parent::render($view, $layout);
        /*
        if (!empty($this->request->params['bare'])) {
            $this->getView()->autoLayout = false;
        }

        $event = $this->dispatchEvent('Controller.beforeRender');
        if ($event->result instanceof Response) {
            $this->autoRender = false;
            return $event->result;
        }
        if ($event->isStopped()) {
            $this->autoRender = false;
            return $this->response;
        }

        $this->autoRender = false;
        $this->response->body($this->getView()->render($view, $layout));
        return $this->response;
        */
    }


    protected function module($moduleName)
    {
        $this->set('moduleName', $moduleName);
        $this->set('moduleTemplate', null);
    }

    protected function cell($cellName)
    {
        $this->set('cellName', $cellName);
    }

}
