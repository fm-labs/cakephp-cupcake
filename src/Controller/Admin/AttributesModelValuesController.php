<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * AttributesModelValues Controller
 *
 * @property \Banana\Model\Table\AttributesModelValuesTable $AttributesModelValues
 */
class AttributesModelValuesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['AttributeSets', 'Attributes']
        ];
        $this->set('attributesModelValues', $this->paginate($this->AttributesModelValues));
        $this->set('_serialize', ['attributesModelValues']);
    }

    /**
     * View method
     *
     * @param string|null $id Attributes Model Value id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attributesModelValue = $this->AttributesModelValues->get($id, [
            'contain' => ['AttributeSets', 'Attributes']
        ]);
        $this->set('attributesModelValue', $attributesModelValue);
        $this->set('_serialize', ['attributesModelValue']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attributesModelValue = $this->AttributesModelValues->newEntity();
        if ($this->request->is('post')) {
            $attributesModelValue = $this->AttributesModelValues->patchEntity($attributesModelValue, $this->request->data);
            if ($this->AttributesModelValues->save($attributesModelValue)) {
                $this->Flash->success(__('The {0} has been saved.', __('attributes model value')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attributes model value')));
            }
        }
        $attributeSets = $this->AttributesModelValues->AttributeSets->find('list', ['limit' => 200]);
        $attributes = $this->AttributesModelValues->Attributes->find('list', ['limit' => 200]);
        $this->set(compact('attributesModelValue', 'attributeSets', 'attributes'));
        $this->set('_serialize', ['attributesModelValue']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Attributes Model Value id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attributesModelValue = $this->AttributesModelValues->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attributesModelValue = $this->AttributesModelValues->patchEntity($attributesModelValue, $this->request->data);
            if ($this->AttributesModelValues->save($attributesModelValue)) {
                $this->Flash->success(__('The {0} has been saved.', __('attributes model value')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attributes model value')));
            }
        }
        $attributeSets = $this->AttributesModelValues->AttributeSets->find('list', ['limit' => 200]);
        $attributes = $this->AttributesModelValues->Attributes->find('list', ['limit' => 200]);
        $this->set(compact('attributesModelValue', 'attributeSets', 'attributes'));
        $this->set('_serialize', ['attributesModelValue']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Attributes Model Value id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attributesModelValue = $this->AttributesModelValues->get($id);
        if ($this->AttributesModelValues->delete($attributesModelValue)) {
            $this->Flash->success(__('The {0} has been deleted.', __('attributes model value')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('attributes model value')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
