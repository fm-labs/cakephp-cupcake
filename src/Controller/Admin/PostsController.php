<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;
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
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $content = $this->Posts->newEntity();
        if ($this->request->is('post')) {
            $content = $this->Posts->patchEntity($content, $this->request->data);
            if ($this->Posts->save($content)) {
                $this->Flash->success(__('The {0} has been saved.', __('content')));

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
                            $this->Flash->success(__('Content module has been created for post {0}', $content->id));
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
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        } else {
            $this->Posts->patchEntity($content, $this->request->query);
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
                $this->Flash->success(__('The {0} has been saved.', __('content')));
                //return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        }

        $this->set(compact('content'));
        $this->set('_serialize', ['content']);
    }

    public function setImage($id)
    {
        //$this->viewBuilder()->layout('Backend.iframe');

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
                $this->Flash->success(__('The {0} has been saved.', __('content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        } else {
        }

        $mm = MediaManager::get('default');
        $files = $mm->getSelectListRecursiveGrouped();
        $this->set('imageFiles', $files);
        $this->set('scope', $scope);
        $this->set('multiple', $multiple);

        $this->set(compact('content'));
        $this->set('_serialize', ['content']);
    }
}
