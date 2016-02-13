<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 3:02 PM
 */

namespace Banana\Controller\Admin;

use Banana\Core\Banana;
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

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentPages'],
            'order' => ['Pages.lft ASC'],
            'limit' => 200,
            'maxLimit' => 200,
        ];

        $pagesTree = $this->Pages->find('treeList')->toArray();
        $this->set('pagesTree', $pagesTree);

        $this->set('contents', $this->paginate($this->Pages));
        $this->set('_serialize', ['contents']);
    }

    public function quick()
    {
        if ($this->request->is(['post','put'])) {
            $id = $this->request->data('page_id');
            if ($id) {
                $this->redirect(['action' => 'view', $id]);
                return;
            }
        }

        $this->Flash->error('Bad Request');
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function table()
    {

    }

    public function treeData()
    {
        $this->viewBuilder()->className('Json');

        $id = $this->request->query('id');
        //debug($id);

        if ($id == '#') {
            $pages = $this->Pages->find()->where(['parent_id IS NULL'])->all()->toArray();
        } else {
            $pages = $this->Pages->find()->where(['parent_id' => $id])->all()->toArray();
        }

        //debug($pages);
        $treeData = [];
        array_walk($pages, function ($val) use (&$treeData, &$id) {
            $treeData[] = [
                'id' => $val->id,
                'text' => $val->title . " (". $val->id . ")",
                'children' => true,
                'icon' => 'ui outline file icon',
                'parent' => ($val->parent_id) ?: '#'
            ];
        });

        $this->set('treeData', $treeData);
        $this->set('_serialize', 'treeData');
    }

    public function treeView()
    {
        $id = $this->request->query('id');
        $this->setAction('view', $id);
    }

    public function add()
    {
        $content = $this->Pages->newEntity();
        if ($this->request->is('post')) {
            $content = $this->Pages->patchEntity($content, $this->request->data);
            if ($this->Pages->save($content)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','content')));
                debug($content->errors());
            }
        }

        $pagesTree = $this->Pages->find('treeList')->toArray();
        $this->set('pagesTree', $pagesTree);

        $this->set('types', $this->_getPageTypes());
        $this->set(compact('content'));
        $this->set('_serialize', ['content']);

    }

    public function linkModule($id = null)
    {
        $contentModule = $this->Pages->ContentModules->newEntity(
            ['refscope' => 'Banana.Pages', 'refid' => $id],
            ['validate' => false]
        );
        if ($this->request->is(['post', 'put'])) {
            $this->Pages->ContentModules->patchEntity($contentModule, $this->request->data);
            if ($this->Pages->ContentModules->save($contentModule)) {
                $this->Flash->success(__d('banana','The content module has been saved for Page {0}.', $id));
            } else {
                $this->Flash->error(__d('banana','The content module could not be saved for Page {0}.', $id));
            }
            return $this->redirect(['action' => 'edit', $id]);
        }
    }

    public function edit($id = null)
    {
        $content = $this->Pages->get($id, [
            'contain' => ['PageLayouts', 'Posts']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->Pages->patchEntity($content, $this->request->data);
            if ($this->Pages->save($content)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','content')));
            }
        }
        $pagesTree = $this->Pages->find('treeList')->toArray();
        $this->set('pagesTree', $pagesTree);

        $this->set('types', Banana::getAvailablePageTypes());
        $this->set('pageLayouts', Banana::getAvailablePageLayouts());
        $this->set('pageTemplates', Banana::getAvailablePageTemplates());

        $this->set('content', $content);
        $this->set('_serialize', ['content']);
    }

    public function view($id = null)
    {
        $content = $this->Pages->get($id, [
            'contain' => ['ContentModules' => ['Modules'], 'PageLayouts']
        ]);

        $posts = $this->Pages->Posts->find()->where(['refid' => $id])->order(['Posts.order' => 'DESC']);
        $content->posts = $posts;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->Pages->patchEntity($content, $this->request->data);
            if ($this->Pages->save($content)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','content')));
            }
        }

        //@TODO Read custom sections from page layout
        $sections = ['main', 'top', 'bottom', 'before', 'after', 'left', 'right'];
        $sections = array_combine($sections, $sections);
        $this->set('sections', $sections);


        //$sectionsModules = $this->Pages->ContentModules->find()->where(['refscope' => 'Banana.Pages', 'refid' => $id]);
        //debug($sectionsModules);

        $availableModules = $this->Pages->ContentModules->Modules->find('list');
        $this->set('availableModules', $availableModules);

        $this->set('content', $content);
        $this->set('_serialize', ['content']);
    }



    public function preview($id = null)
    {
        $page = $this->Pages->get($id);
        $this->redirect($page->url);
    }

    /**
     * @deprecated Use Banana::getPageTypes() instead
     */
    protected function _getPageTypes()
    {
        return Banana::getAvailablePageTypes();
    }

    public function moveUp($id = null) {
        $page = $this->Pages->get($id, ['contain' => []]);

        if ($this->Pages->moveUp($page)) {
            $this->Flash->success(__d('banana','The {0} has been moved up.', __d('banana','page')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be moved. Please, try again.', __d('banana','page')));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function moveDown($id = null) {
        $page = $this->Pages->get($id, ['contain' => []]);

        if ($this->Pages->moveDown($page)) {
            $this->Flash->success(__d('banana','The {0} has been moved down.', __d('banana','page')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be moved. Please, try again.', __d('banana','page')));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function repair()
    {
        $this->Pages->recover();
        $this->Flash->success(__d('banana','Shop Category tree recovery has been executed'));
        $this->redirect($this->referer(['action' => 'index']));
    }

}
