<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;
use Cake\Network\Exception\NotFoundException;

/**
 * Modules Controller
 *
 * @property \Banana\Model\Table\ModulesTable $Modules
 */
class ModulesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('modules', $this->paginate($this->Modules));
        $this->set('_serialize', ['modules']);
    }

    /**
     * View method
     *
     * @param string|null $id Module id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $module = $this->Modules->get($id, [
            'contain' => []
        ]);
        $this->set('module', $module);
        $this->set('_serialize', ['module']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $module = $this->Modules->newEntity();
        if ($this->request->is('post')) {
            $module = $this->Modules->patchEntity($module, $this->request->data);
            if ($this->Modules->save($module)) {
                $this->Flash->success(__('The {0} has been saved.', __('module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('module')));
            }
        }
        $this->set(compact('module'));
        $this->set('_serialize', ['module']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Module id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $module = $this->Modules->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $module = $this->Modules->patchEntity($module, $this->request->data);
            if ($this->Modules->save($module)) {
                $this->Flash->success(__('The {0} has been saved.', __('module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('module')));
            }
        }
        $this->set(compact('module'));
        $this->set('_serialize', ['module']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Module id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $module = $this->Modules->get($id);
        if ($this->Modules->delete($module)) {
            $this->Flash->success(__('The {0} has been deleted.', __('module')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('module')));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function duplicate($id = null)
    {
        $content = $this->Modules->get($id);
        if (!$content) {
            throw new NotFoundException();
        }

        $duplicate = $this->Modules->duplicate($content);
        if ($this->Modules->save($duplicate)) {
            $this->Flash->success(__('The {0} has been duplicated.', __('module')));
            return $this->redirect(['action' => 'edit', $duplicate->id]);
        } else {
            $this->Flash->error(__('The {0} could not be duplicated. Please, try again.', __('module')));
            return $this->redirect($this->referer(['action' => 'index']));
        }
    }
}
