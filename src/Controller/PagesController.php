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
use Cake\Routing\Router;
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

    public $viewClass = 'Banana.Page';

    /**
     * Indicates root page
     * @var bool
     */
    protected $_root = false;

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Banana.Frontend');
        $this->loadComponent('Paginator');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->plugin('Banana');
        $this->autoRender = false;
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    public function index()
    {
        $rootPage = $this->Pages->findHostRoot();
        if (!$rootPage) {
            throw new NotFoundException(__d('banana',"Root page not found"));
        }

        $this->setAction('view', $rootPage->id);
    }

    public function tree()
    {

    }

    public function viewSlug($slug = null)
    {
        $page = $this->Pages->findBySlug($slug);
        $this->setAction('view', $page->id);
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
                case $this->request->query('page_id'):
                    $id = $this->request->query('page_id');
                    break;
                case $this->request->param('page_id'):
                    $id = $this->request->param('page_id');
                    break;
                case $this->request->query('slug'):
                    $id = $this->Pages->findIdBySlug($this->request->query('slug'));
                    break;
                case $this->request->param('slug'):
                    $id = $this->Pages->findIdBySlug($this->request->param('slug'));
                    break;
                default:
                    //throw new NotFoundException();
            }
        }

        $page = $this->Pages
            ->find('published')
            ->where(['Pages.id' => $id])
            ->contain(['PageLayouts'])
            ->first();


        if (!$page) {
            throw new NotFoundException(__d('banana',"Page not found"));
        }

        $this->Frontend->setRefId($page->id);

        switch ($page->type) {
            // Internal redirects
            case 'root':
                $this->_root = true; // root flag

            case 'page':
                return $this->setAction('view', $page->redirect_page_id);

            // Http redirect
            case 'redirect':
                if ($page->redirect_page_id) {
                    $page = $this->Pages->get($page->redirect_page_id, ['contain' => []]);
                    $redirectUrl = $page->url;
                } else {
                    $redirectUrl = $page->redirect_location;
                }

                return $this->redirect($redirectUrl, $page->redirect_status);
            case 'controller':
                return $this->redirect($page->redirect_controller_url, $page->redirect_status);

            // Inline types
            case 'cell':
                $cellName = $page->redirect_controller;
                $this->setAction('cell', $cellName);
                break;

            case 'module':
                $moduleName = $page->redirect_controller;
                $this->setAction('module', $moduleName);
                break;

            // Static
            case 'static':
                $action = ($page->page_template) ?: null;
                if ($action !== 'view' && $action && method_exists($this, $action)) {
                    $this->setAction($action);
                }
                break;

            case 'blog_category':
                $query = $this->Pages->Posts
                    ->find('published')
                    ->where(['refscope' => 'Banana.Pages', 'refid' => $page->id]);

                $posts = $this->Paginator->paginate($query);
                $this->set('posts', $posts);
                break;

            // Content
            case 'content':
            default:
                break;
        }

        // force canonical url (except root pages)
        if (Configure::read('Banana.Router.forceCanonical') && !$this->_root) {
            $here = Router::normalize($this->request->here);
            $canonical = Router::normalize($page->url);

            if ($here != $canonical) {
                $this->redirect($canonical, 301);
                return;
            }
        }

        $this->autoRender = false;
        $this->viewBuilder()->className('Banana.Page');

        $view = ($page->page_template) ?: $page->type;
        $view = ($view == 'content') ? 'view' : $view;
        $this->viewBuilder()->template($view);

        $layout = ($page->page_layout) ? $page->page_layout->template : null;
        $this->viewBuilder()->layout($layout);

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
