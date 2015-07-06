<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * PageModules Controller
 *
 * @property \Banana\Model\Table\PageModulesTable $PageModules
 */
class PageModulesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Pages', 'Modules']
        ];
        $this->set('pageModules', $this->paginate($this->PageModules));
        $this->set('_serialize', ['pageModules']);
    }

    /**
     * View method
     *
     * @param string|null $id Page Module id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pageModule = $this->PageModules->get($id, [
            'contain' => ['Pages', 'Modules']
        ]);
        $this->set('pageModule', $pageModule);
        $this->set('_serialize', ['pageModule']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pageModule = $this->PageModules->newEntity();
        if ($this->request->is('post')) {
            $pageModule = $this->PageModules->patchEntity($pageModule, $this->request->data);
            if ($this->PageModules->save($pageModule)) {
                $this->Flash->success(__('The {0} has been saved.', __('page module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('page module')));
            }
        }
        $pages = $this->PageModules->Pages->find('list', ['limit' => 200]);
        $modules = $this->PageModules->Modules->find('list', ['limit' => 200]);
        $this->set(compact('pageModule', 'pages', 'modules'));
        $this->set('_serialize', ['pageModule']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Page Module id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pageModule = $this->PageModules->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pageModule = $this->PageModules->patchEntity($pageModule, $this->request->data);
            if ($this->PageModules->save($pageModule)) {
                $this->Flash->success(__('The {0} has been saved.', __('page module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('page module')));
            }
        }
        $pages = $this->PageModules->Pages->find('list', ['limit' => 200]);
        $modules = $this->PageModules->Modules->find('list', ['limit' => 200]);
        $this->set(compact('pageModule', 'pages', 'modules'));
        $this->set('_serialize', ['pageModule']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Page Module id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pageModule = $this->PageModules->get($id);
        if ($this->PageModules->delete($pageModule)) {
            $this->Flash->success(__('The {0} has been deleted.', __('page module')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('page module')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
