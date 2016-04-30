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
use Cake\ORM\TableRegistry;
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
    public function table()
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

    public function index()
    {

    }

    public function treeData()
    {
        $this->viewBuilder()->className('Json');

        $id = $this->request->query('id');
        if ($id == '#') {
            $pages = $this->Pages->find()->where(['parent_id IS NULL'])->all()->toArray();
        } else {
            $pages = $this->Pages->find()->where(['parent_id' => $id])->all()->toArray();
        }

        //debug($pages);
        $treeData = [];
        array_walk($pages, function ($val) use (&$treeData, &$id) {
            $publishedClass = ($val->is_published) ? 'published' : 'unpublished';
            $treeData[] = [
                'id' => $val->id,
                'text' => $val->title . " (". $val->id . ")",
                'children' => true,
                'icon' => $val->type . " " . $publishedClass,
                'parent' => ($val->parent_id) ?: '#'
            ];
        });

        $this->set('treeData', $treeData);
        $this->set('_serialize', 'treeData');
    }

    public function treeView()
    {
        $id = $this->request->query('id');
        $this->setAction('manage', $id);
    }

    public function manage($id = null)
    {
        $content = $this->Pages->get($id, [
            'contain' => ['ParentPages']
        ]);

        $this->set('content', $content);
        $this->set('_serialize', ['content']);
    }

    public function relatedPosts($id = null)
    {
        $content = $this->Pages->get($id, [
            'contain' => []
        ]);

        $posts = $this->Pages->Posts
            ->find('sorted')
            ->where(['refid' => $id])
            //->order(['Posts.pos' => 'DESC'])
            ->all();


        $this->set('content', $content);
        $this->set('posts', $posts);
        $this->set('_serialize', ['content', 'posts']);
    }

    public function relatedPageMeta($id = null)
    {
        $PageMetas = TableRegistry::get('Banana.PageMetas');

        $content = $this->Pages->get($id, [
            'contain' => []
        ]);

        $pageMeta = $content->meta;
        if (!$pageMeta) {
            $pageMeta = $PageMetas->newEntity(
                ['model' => 'Banana.Pages', 'foreignKey' => $content->id],
                ['validate' => false]
            );
        }

        if ($this->request->is(['put', 'post'])) {
            $pageMeta = $PageMetas->patchEntity($pageMeta, $this->request->data);
            if ($PageMetas->save($pageMeta)) {
                $this->Flash->success('Successful');
            } else {
                $this->Flash->error('Failed');
            }
        }

        $this->set('content', $content);
        $this->set('pageMeta', $pageMeta);
        $this->set('_serialize', ['content', 'pageMeta']);
    }

    public function relatedContentModules($id = null)
    {

        $content = $this->Pages->get($id, [
            'contain' => ['ContentModules' => ['Modules']]
        ]);


        //@TODO Read custom sections from page layout
        $sections = ['main', 'top', 'bottom', 'before', 'after', 'left', 'right'];
        $sections = array_combine($sections, $sections);

        //$sectionsModules = $this->Pages->ContentModules->find()->where(['refscope' => 'Banana.Pages', 'refid' => $id]);
        //debug($sectionsModules);

        $availableModules = $this->Pages->ContentModules->Modules->find('list');

        $this->set('content', $content);
        $this->set('sections', $sections);
        $this->set('availableModules', $availableModules);

        $this->set('_serialize', ['content', 'sections', 'availableModules']);
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

        $posts = $this->Pages->Posts->find()->where(['refid' => $id])->order(['Posts.pos' => 'DESC']);
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
