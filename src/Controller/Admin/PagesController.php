<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 3:02 PM
 */

namespace Banana\Controller\Admin;

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

        $treeList = $this->Pages->find('treeList', [
            /*
            'valuePath' => function ($page) {
                $path = $this->Pages->find('path', ['for' => $page->id]);

                $pathStr = "";
                foreach ($path as $part) {
                    $pathStr .= $part->slug . '/';
                }

                return $pathStr;
            },
            */
            'spacer' => '_'
        ]);
        $parentPages = $this->Pages->ParentPages->find('list', ['limit' => 200]);

        $this->set('parentPages', $parentPages);
        $this->set('treeList', $treeList->toArray());
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentPages']
        ];

        $rootNode = $this->Pages->find()->where(['parent_id IS NULL'])->first();
        $children = [];

        if ($rootNode) {
            $children = $this->Pages
                ->find('children', ['for' => $rootNode->id])
                ->find('threaded');
        }


        $this->set('contents', $this->paginate($this->Pages));
        $this->set('children', $children);
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
            'contain' => ['ContentModules' => ['Modules']]
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
        $this->set('types', $this->_getPageTypes());
        $this->set(compact('content'));
        $this->set('_serialize', ['content']);
    }

    public function preview($id = null)
    {
        $this->redirect(['prefix' => false, 'plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view', $id]);
    }

    protected function _getPageTypes()
    {
        return [
            'content' => 'Content',
            'controller' => 'Controller',
            'module' => 'Module',
            'page' => 'Page',
            'redirect' => 'Redirect',
            'root' => 'Website Root',
        ];
    }

}
