<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 3:02 PM
 */

namespace Banana\Controller\Admin;

use Banana\Form\ModuleParamsForm;
use Banana\View\Module\BaseModule;
use Banana\View\ViewModule;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Banana\Model\Table\PagesTable;
use Cake\Network\Exception\NotFoundException;
use Banana\Model\Table\ModulesTable;

/**
 * Class PagesController
 * @package App\Controller\Admin
 *
 * @property PagesTable $Pages
 * @property ModulesTable $Modules
 */
class PagesController extends AppController
{
    public $modelClass = "Banana.Pages";

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        //$this->loadModel("Banana.Pages");
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

        $children = $this->Pages
            ->find('children', ['for' => 1])
            ->find('threaded');

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

        $this->set('pages', $this->paginate($this->Pages));
        $this->set('children', $children);
        $this->set('treeList', $treeList->toArray());
        $this->set('_serialize', ['pages']);
    }
    /**
     * View method
     *
     * @param string|null $id Page id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $page = $this->Pages->get($id, [
            'contain' => ['ParentPages', 'ChildPages']
        ]);
        $this->set('page', $page);
        $this->set('_serialize', ['page']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $page = $this->Pages->newEntity();
        if ($this->request->is('post')) {
            $page = $this->Pages->patchEntity($page, $this->request->data);
            if ($this->Pages->save($page)) {
                $this->Flash->success(__('The {0} has been saved.', __('page')));
                return $this->redirect(['action' => 'edit', $page->id]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('page')));
            }
        }
        $parentPages = $this->Pages->ParentPages->find('list', ['limit' => 200]);
        $this->set(compact('page', 'parentPages'));
        $this->set('_serialize', ['page']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Page id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $page = $this->Pages->get($id, [
            'contain' => ['PageModules' => ['Modules']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $page = $this->Pages->patchEntity($page, $this->request->data);
            if ($this->Pages->save($page)) {
                $this->Flash->success(__('The {0} has been saved.', __('page')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('page')));
            }
        }
        $parentPages = $this->Pages->ParentPages->find('list', ['limit' => 200]);
        $this->set(compact('page', 'parentPages'));
        $this->set('availableModules', $this->getAvailableModules());
        $this->set('_serialize', ['page']);
    }

    public function edit_modules($id = null)
    {
        $page = $this->Pages->get($id, [
            'contain' => ['PageModules' => ['Modules']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $page = $this->Pages->patchEntity($page, $this->request->data);
            if ($this->Pages->save($page)) {
                $this->Flash->success(__('The {0} has been saved.', __('page')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('page')));
            }
        }
        $parentPages = $this->Pages->ParentPages->find('list', ['limit' => 200]);
        $this->set(compact('page', 'parentPages'));
        $this->set('availableModules', $this->getAvailableModules());
        $this->set('_serialize', ['page']);
    }


    public function edit_module($moduleId = null)
    {
        $this->loadModel('Banana.Modules');
        //$page = $this->Pages->get($id);
        $module = $this->Modules->get($moduleId);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $params = json_encode($this->request->data);
            $module = $this->Modules->patchEntity($module, ['params' => $params]);
            if ($this->Modules->save($module)) {
                $this->Flash->success(__('The {0} has been saved.', __('module')));
                $this->redirect(['action' => 'edit_module', $moduleId]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('page')));
            }
        }

        $this->set(compact('page', 'module'));
        $this->set('_serialize', ['page', 'module']);
    }

    public function add_page_module()
    {
        $pageId = $this->request->query('page');
        $modulePath = $this->request->query('module');
        $isAjax = ($this->request->query('ajax') || $this->request->is('ajax'));

        $page = $this->Pages->get($pageId);
        if (!$page) {
            throw new NotFoundException("Page with ID %s not found", $pageId);
        }

        $class = ViewModule::className($modulePath);
        if (!$class || !class_exists($class)) {
            throw new Exception(sprintf("Module class '%s' not found", $class));
        }

        $moduleClass = $class;
        $moduleSchema = $class::schema();
        $moduleFormInputs = $class::inputs();
        $moduleParams = [];

        $form = new ModuleParamsForm();
        $form->schema($moduleSchema);

        $this->loadModel('Banana.Modules');
        $module = $this->Modules->newEntity();

        if ($this->request->is('post')) {
            // verify module params form
            if ($form->execute($this->request->data)) {
                // module params are valid
                // now create page module
                $moduleParams = $this->request->data();
                $module = $this->Modules->patchEntity($module, [
                    'name' => sprintf('pages.%s.%s', $page->id, uniqid()),
                    'path' => $modulePath,
                    'params' => json_encode($moduleParams),
                ]);

                $pageModule = $this->Pages->PageModules->newEntity();
                $pageModule->page = $page;
                $pageModule->module = $module;

                if ($this->Pages->PageModules->save($pageModule)) {
                    $this->Flash->success(__('Module {0} has been added to page {1}', $pageModule->module_id, $pageModule->page_id));
                    $this->redirect(['action' => 'edit', $page->id]);
                } else {
                    $this->Flash->error('Ups. Something went wrong while creating the page module.');
                }

            } else {
                $this->Flash->error('Please check your module params.');
            }
        } else {
            $this->request->data = $moduleParams;
        }

        if ($isAjax) {
            $this->layout = "Banana.ajax";
        }

        $this->set('moduleClass', $moduleClass);
        $this->set('modulePath', $modulePath);
        $this->set('moduleParams', $moduleParams);
        $this->set('moduleSchema', $moduleSchema);
        $this->set('moduleForm', $form);
        $this->set('moduleFormInputs', $moduleFormInputs);

        $this->set('page', $page);
        $this->set('module', $module);
    }

    /**
     * Delete method
     *
     * @param string|null $id Page id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $page = $this->Pages->get($id);
        if ($this->Pages->delete($page)) {
            $this->Flash->success(__('The {0} has been deleted.', __('page')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('page')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
