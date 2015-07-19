<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * ContentModules Controller
 *
 * @property \Banana\Model\Table\ContentModulesTable $ContentModules
 */
class ContentModulesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Modules']
        ];
        $this->set('contentModules', $this->paginate($this->ContentModules));
        $this->set('_serialize', ['contentModules']);
    }

    /**
     * View method
     *
     * @param string|null $id Content Module id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contentModule = $this->ContentModules->get($id, [
            'contain' => ['Modules']
        ]);
        $this->set('contentModule', $contentModule);
        $this->set('_serialize', ['contentModule']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contentModule = $this->ContentModules->newEntity();
        if ($this->request->is('post')) {
            $contentModule = $this->ContentModules->patchEntity($contentModule, $this->request->data);
            if ($this->ContentModules->save($contentModule)) {
                $this->Flash->success(__('The {0} has been saved.', __('content module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content module')));
            }
        }
        $modules = $this->ContentModules->Modules->find('list', ['limit' => 200]);
        $this->set(compact('contentModule', 'modules'));
        $this->set('_serialize', ['contentModule']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Content Module id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contentModule = $this->ContentModules->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contentModule = $this->ContentModules->patchEntity($contentModule, $this->request->data);
            if ($this->ContentModules->save($contentModule)) {
                $this->Flash->success(__('The {0} has been saved.', __('content module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('content module')));
            }
        }
        $modules = $this->ContentModules->Modules->find('list', ['limit' => 200]);
        $this->set(compact('contentModule', 'modules'));
        //$this->set('templates', $this->getModuleTemplatesAvailable());
        $this->set('_serialize', ['contentModule']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Content Module id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contentModule = $this->ContentModules->get($id);
        if ($this->ContentModules->delete($contentModule)) {
            $this->Flash->success(__('The {0} has been deleted.', __('content module')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('content module')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
