<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/17/15
 * Time: 6:34 PM
 */

namespace Banana\Controller;

use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Banana\Model\Table\PagesTable;

/**
 * Class FrontendController
 * @package App\Controller
 *
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
        //@TODO sanitize params
        //@TODO Override theme if page has a theme assigned
        //@TODO Override layout if page has a layout assigned

        if ($id) {
            // by id
            $page = $this->Pages->get($id);
        } elseif ($this->request->param('id')) {
            $page = $this->Pages->get($this->request->param('id'));
        } elseif (!$id && $this->request->param('slug')) {
            // by slug
            $slug = $this->request->param('slug');
            $page = $this->Pages->find()->where(['slug' => $slug])->first();
        } else {
            throw new BadRequestException();
        }

        if (!$page) {
            throw new NotFoundException("No pages");
        }

        if ($page->type == 'redirect') {
            return $this->redirect($page->redirect_location);
        } elseif ($page->type == 'controller') {
            list($plugin, $controller) = pluginSplit($page->redirect_location);
            $url = ['plugin' => $plugin, 'controller' => $controller, 'action' => 'index'];
            return $this->redirect($url);
        }


        $this->set('title', $page->title);
        $this->set('page', $page);
        $this->set('pageId', $page->id);
        $this->request->params['page_id'] = $page->id;
    }
}
