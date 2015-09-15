<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 3:02 PM
 */

namespace Banana\Controller\Admin;

use Banana\Lib\Banana;
use Banana\Model\Table\PagesTable;
use Cake\Event\Event;
use Tree\Controller\TreeSortControllerTrait;

/**
 * Class PagesController
 * @package App\Controller\Admin
 *
 * @property PagesTable $Pages
 */
class PagesController extends ContentController
{

    use TreeSortControllerTrait;

    public $modelClass = "Banana.Pages";

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentPages'],
            'order' => ['Pages.lft ASC']
        ];

        $pagesTree = $this->Pages->find('treeList')->toArray();
        $this->set('pagesTree', $pagesTree);

        $this->set('contents', $this->paginate($this->Pages));
        $this->set('_serialize', ['contents']);
    }

    public function add()
    {
        $content = $this->Pages->newEntity();
        if ($this->request->is('post')) {
            $content = $this->Pages->patchEntity($content, $this->request->data);
            if ($this->Pages->save($content)) {
                $this->Flash->success(__('The {0} has been saved.', __('content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
                debug($content->errors());
            }
        }
        $this->set('types', $this->_getPageTypes());
        $this->set(compact('content'));
        $this->set('_serialize', ['content']);

    }

    public function edit($id = null)
    {
        $content = $this->Pages->get($id, [
            'contain' => ['ContentModules' => ['Modules'], 'PageLayouts']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->Pages->patchEntity($content, $this->request->data);
            if ($this->Pages->save($content)) {
                $this->Flash->success(__('The {0} has been saved.', __('content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        }

        //@TODO Read custom sections from page layout
        $sections = ['top', 'bottom', 'before'];
        $sections = array_combine($sections, $sections);
        $this->set('sections', $sections);

        $pagesTree = $this->Pages->find('treeList')->toArray();
        $this->set('pagesTree', $pagesTree);

        $sectionsModules = $this->Pages->ContentModules->find()->where(['refscope' => 'Banana.Pages', 'refid' => $id]);
        //debug($sectionsModules);

        $this->set('types', Banana::getAvailablePageTypes());
        $this->set('pageLayouts', Banana::getAvailablePageLayouts());
        $this->set('pageTemplates', Banana::getAvailablePageTemplates());
        $this->set('content', $content);
        $this->set('_serialize', ['content']);
    }


    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $content = $this->model()->get($id, [
            'contain' => ['ContentModules']
        ]);
        $this->set('page', $content);
        $this->set('_serialize', ['page']);
    }

    public function preview($id = null)
    {
        $this->redirect(['prefix' => false, 'plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view', $id]);
    }

    /**
     * @deprecated Use Banana::getPageTypes() instead
     */
    protected function _getPageTypes()
    {
        return Banana::getAvailablePageTypes();
    }

}
