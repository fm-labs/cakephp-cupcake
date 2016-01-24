<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;
use Banana\Lib\Banana;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Table;
use Media\Lib\Media\MediaManager;

/**
 * Posts Controller
 *
 * @property \Banana\Model\Table\PostsTable $Posts
 */
class PostsController extends ContentController
{
    public $modelClass = 'Banana.Posts';

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $scope = ['Posts.refscope' => 'Banana.Pages'];
        $order = ['Posts.title' => 'ASC'];

        $this->paginate = [
            'contain' => [],
            'order' => $order,
            'limit' => 200,
            'maxLimit' => 200,
            'conditions' => $scope
        ];

        $this->set('postsList', $this->Posts->find('list')->where($scope)->order($order));

        $this->set('contents', $this->paginate($this->Posts));
        $this->set('_serialize', ['contents']);
    }


    public function quick()
    {
        if ($this->request->is(['post','put'])) {
            $id = $this->request->data('post_id');
            if ($id) {
                $this->redirect(['action' => 'edit', $id]);
                return;
            }
        }

        $this->Flash->error('Bad Request');
        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $content = $this->Posts->newEntity([], ['validate' => false]);
        if ($this->request->is('post')) {
            $content = $this->Posts->patchEntity($content, $this->request->data);
            if ($this->Posts->save($content)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','content')));

                /*
                if ($link == true && $refid && $refscope) {
                    $this->loadModel('Banana.Modules');
                    $module = $this->Modules->newEntity([
                        'name' => sprintf('Post_%s_%s', $content->id, uniqid()),
                        'path' => 'Banana.PostsView',
                    ]);
                    $module->setParams(['post_id' => $content->id]);

                    if ($this->Modules->save($module)) {
                        $this->loadModel('Banana.ContentModules');
                        $contentModule = $this->ContentModules->newEntity();
                        $contentModule->refscope = $refscope;
                        $contentModule->refid = $refid;
                        $contentModule->module_id = $module->id;
                        $contentModule->section = 'main';

                        if ($this->ContentModules->save($contentModule)) {
                            $this->Flash->success(__d('banana','Content module has been created for post {0}', $content->id));
                        } else {
                            debug($contentModule->errors());
                        }
                    } else {
                        debug($module->errors());
                    }
                }
                */

                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','content')));
            }
        } else {
            $this->Posts->patchEntity($content, $this->request->query, ['validate' => false]);
        }


        $this->set(compact('content'));
        $this->set('_serialize', ['content']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $content = $this->Posts->get($id, [
            'contain' => ['ContentModules' => ['Modules']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->Posts->patchEntity($content, $this->request->data);
            if ($this->Posts->save($content)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','content')));
            }
        }

        $teaserTemplates = Banana::getAvailablePostTeaserTemplates();
        $templates = Banana::getAvailablePostTemplates();

        $this->set(compact('content', 'teaserTemplates', 'templates'));
        $this->set('_serialize', ['content']);
    }

    public function setImage($id = null)
    {
        $scope = $this->request->query('scope');
        $multiple = $this->request->query('multiple');

        $this->Posts->behaviors()->unload('Media');
        $content = $this->Posts->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->Posts->patchEntity($content, $this->request->data);
            //$content->$scope = $this->request->data[$scope];
            if ($this->Posts->save($content)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','content')));

                if (!$this->request->is('iframe')) {
                    return $this->redirect(['action' => 'edit', $content->id]);
                }
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','content')));
            }
        } else {
        }

        $mm = MediaManager::get('images');
        $files = $mm->getSelectListRecursiveGrouped();
        $this->set('imageFiles', $files);
        $this->set('scope', $scope);
        $this->set('multiple', $multiple);

        $this->set(compact('content'));
        $this->set('_serialize', ['content']);
    }

    public function deleteImage($id = null)
    {
        $scope = $this->request->query('scope');

        $this->Posts->behaviors()->unload('Media');
        $content = $this->Posts->get($id, [
            'contain' => []
        ]);

        if (!in_array($scope, ['teaser_image_file', 'image_file', 'image_files'])) {
            throw new BadRequestException('Invalid scope');
        }

        $content->accessible($scope, true);
        $content->set($scope, '');

        if ($this->Posts->save($content)) {
            $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','content')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','content')));
        }
        return $this->redirect(['action' => 'edit', $content->id]);
    }
}
