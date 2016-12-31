<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * Attributes Controller
 *
 * @property \Banana\Model\Table\AttributesTable $Attributes
 */
class AttributesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('attributes', $this->paginate($this->Attributes));
        $this->set('_serialize', ['attributes']);
    }

    /**
     * View method
     *
     * @param string|null $id Attribute id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attribute = $this->Attributes->get($id, [
            'contain' => []
        ]);
        $this->set('attribute', $attribute);
        $this->set('_serialize', ['attribute']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attribute = $this->Attributes->newEntity();
        if ($this->request->is('post')) {
            $attribute = $this->Attributes->patchEntity($attribute, $this->request->data);
            if ($this->Attributes->save($attribute)) {
                $this->Flash->success(__('The {0} has been saved.', __('attribute')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attribute')));
            }
        }
        $this->set(compact('attribute'));
        $this->set('_serialize', ['attribute']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Attribute id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attribute = $this->Attributes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attribute = $this->Attributes->patchEntity($attribute, $this->request->data);
            if ($this->Attributes->save($attribute)) {
                $this->Flash->success(__('The {0} has been saved.', __('attribute')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attribute')));
            }
        }
        $this->set(compact('attribute'));
        $this->set('_serialize', ['attribute']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Attribute id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attribute = $this->Attributes->get($id);
        if ($this->Attributes->delete($attribute)) {
            $this->Flash->success(__('The {0} has been deleted.', __('attribute')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('attribute')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
