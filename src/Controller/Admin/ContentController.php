<?php
namespace Banana\Controller\Admin;

use Banana\Form\ModuleParamsForm;
use Banana\View\ViewModule;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Form\Form;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\Table;
use Banana\Lib\Banana;
use Media\Lib\Media\MediaManager;

abstract class ContentController extends AppController
{
    protected $Model;

    public $modelClass = null;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if (!$this->modelClass) {
            throw new Exception('No modelClass defined in controller ' . get_called_class());
        }
    }

    public function beforeRender(Event $event)
    {
        $this->set('layoutsAvailable', $this->getLayoutsAvailable());
        $this->set('modulesAvailable', $this->getModulesAvailable());
        $this->set('moduleTemplatesAvailable', $this->getModuleTemplatesAvailable());
        $this->set('themesAvailable', $this->getThemesAvailable());
    }


    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('contents', $this->paginate($this->model()));
        $this->set('_serialize', ['contents']);
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
        $content = $this->model()->get($id, [
            'contain' => ['ContentModules' => ['Modules']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->model()->patchEntity($content, $this->request->data);
            if ($this->model()->save($content)) {
                $this->Flash->success(__('The {0} has been saved.', __('content')));
                //return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        }


        $mm = MediaManager::get('default');
        $this->set('image_files', $mm->getSelectListRecursive());

        $this->set(compact('content'));
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
            'contain' => []
        ]);
        $this->set('content', $content);
        $this->set('_serialize', ['content']);
    }

    public function preview($id = null)
    {
        $this->redirect(['prefix' => false, 'plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view', $id]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $content = $this->model()->newEntity();
        if ($this->request->is('post')) {
            $content = $this->model()->patchEntity($content, $this->request->data);
            if ($this->model()->save($content)) {
                $this->Flash->success(__('The {0} has been saved.', __('content')));
                return $this->redirect(['action' => 'edit', $content->id]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        }
        $this->set(compact('content'));
        $this->set('_serialize', ['content']);
    }

    public function duplicate($id = null)
    {
        $content = $this->model()->get($id);
        if (!$content) {
            throw new NotFoundException();
        }

        $duplicate = $this->model()->copyEntity($content);
        if ($this->request->is('post')) {
            $duplicate = $this->model()->patchEntity($duplicate, $this->request->data);

            if ($this->model()->save($duplicate)) {
                $this->Flash->success(__('The {0} has been duplicated.', __('content')));
                return $this->redirect(['action' => 'edit', $duplicate->id]);
            } else {
                $this->Flash->error(__('The {0} could not be duplicated. Please, try again.', __('content')));
                return $this->redirect($this->referer(['action' => 'index']));
            }
        }

        $this->set('content', $duplicate);
        $this->render('add');
    }


    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $content = $this->model()->get($id);
        if ($this->model()->delete($content)) {
            $this->Flash->success(__('The {0} has been deleted.', __('content')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('content')));
        }
        return $this->redirect(['action' => 'index']);
    }


    public function edit_modules($id = null)
    {
        $content = $this->model()->get($id, [
            'contain' => ['ContentModules' => ['Modules']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->model()->patchEntity($content, $this->request->data);
            if ($this->model()->save($content)) {
                $this->Flash->success(__('The {0} has been saved.', __('content')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        }
        $parentPages = $this->model()->ParentPages->find('list', ['limit' => 200]);
        $this->set(compact('content', 'parentPages'));
        $this->set('_serialize', ['content']);
    }


    public function edit_module($moduleId = null)
    {
        $this->loadModel('Banana.Modules');
        //$content = $this->model()->get($id);
        $module = $this->Modules->get($moduleId);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $params = json_encode($this->request->data);
            $module = $this->Modules->patchEntity($module, ['params' => $params]);
            if ($this->Modules->save($module)) {
                $this->Flash->success(__('The {0} has been saved.', __('module')));
                $this->redirect(['action' => 'edit_module', $moduleId]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content')));
            }
        }

        $this->set(compact('content', 'module'));
        $this->set('_serialize', ['content', 'module']);
    }

    public function addPost($contentId = null)
    {
        $this->redirect([
            'controller' => 'Posts',
            'action' => 'add',
            'refscope' => 'Banana.Pages',
            'refid' => $contentId,
            'link' => true
        ]);
    }


    public function addContentModule($contentId = null)
    {
        if (!$contentId) {
            $contentId = $this->request->query('content_id');
        }

        $isAjax = ($this->request->query('ajax') || $this->request->is('ajax'));
        $isIframe = $this->request->query('iframe');

        if ($isIframe || $isAjax) {
            $this->layout = "iframe_module";
        }

        $content = $this->model()->get($contentId);
        if (!$content) {
            throw new NotFoundException("Page with ID %s not found", $contentId);
        }

        $this->loadModel('Banana.ContentModules');
        $contentModule = $this->ContentModules->newEntity();


        if ($this->request->is('post')) {
            $contentModule = $this->ContentModules->patchEntity($contentModule, $this->request->data);
            debug($contentModule);
            if ($this->ContentModules->save($contentModule)) {
                $this->Flash->success(__('Module {0} has been added to Content with ID {1}', $contentModule->module, $contentModule->refid));
                //$this->redirect(['action' => 'edit', $content->id]);
            } else {
                debug($contentModule->errors());
                $this->Flash->error('Ups. Something went wrong while creating the content module.');
            }
        } else {
            $contentModule->refid = ($contentModule->refid) ?: $contentId;
        }

        $this->set('availableModules', $this->ContentModules->Modules->find('list'));
        $this->set('sections', Banana::listContentSections());

        $this->set('contentModule', $contentModule);
    }

    public function createModule($contentId)
    {
        $content = $this->model()->get($contentId);

        $form = new Form();

        $module = $this->Modules->newEntity();
        $modulePath = 'Banana.Text/Html';
        $moduleParams = [];

        if ($this->request->is('post')) {
            // verify module params form
            if ($form->execute($this->request->data)) {
                // module params are valid
                // now create content module
                $moduleParams = $this->request->data();
                $module = $this->Modules->patchEntity($module, [
                    'name' => sprintf('Module for Content %s [%s]', $content->id, uniqid()),
                    'path' => $modulePath,
                    'params' => json_encode($moduleParams),
                ]);
                /*
                $contentModule = $this->model()->ContentModules->newEntity();
                $contentModule->refscope = $this->modelClass;
                $contentModule->refid = $contentId;
                $contentModule->content = $content;
                $contentModule->module = $module;
                $contentModule->section = $section;

                if ($this->model()->ContentModules->save($contentModule)) {
                    $this->Flash->success(__('Module {0} has been added to Content with ID {1}', $contentModule->module, $contentModule->refid));
                    $this->redirect(['action' => 'edit', $content->id]);
                } else {
                    $this->Flash->error('Ups. Something went wrong while creating the content module.');
                }
                */

            } else {
                $this->Flash->error('Please check your module params.');
            }
        } else {
            $this->request->data = $moduleParams;
        }
    }

    /**
     * Subclasses can override this method and return the primary model used in the controller
     * @return Table
     */
    protected function model()
    {
        if (!$this->Model) {
            $this->Model = $this->loadModel($this->modelClass);
        }
        return $this->Model;
    }


}
