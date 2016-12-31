<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * AttributeSets Controller
 *
 * @property \Banana\Model\Table\AttributeSetsTable $AttributeSets
 */
class AttributeSetsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('attributeSets', $this->paginate($this->AttributeSets));
        $this->set('_serialize', ['attributeSets']);
    }

    /**
     * View method
     *
     * @param string|null $id Attribute Set id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attributeSet = $this->AttributeSets->get($id, [
            'contain' => []
        ]);
        $this->set('attributeSet', $attributeSet);
        $this->set('_serialize', ['attributeSet']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attributeSet = $this->AttributeSets->newEntity();
        if ($this->request->is('post')) {
            $attributeSet = $this->AttributeSets->patchEntity($attributeSet, $this->request->data);
            if ($this->AttributeSets->save($attributeSet)) {
                $this->Flash->success(__('The {0} has been saved.', __('attribute set')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attribute set')));
            }
        }
        $this->set(compact('attributeSet'));
        $this->set('_serialize', ['attributeSet']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Attribute Set id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attributeSet = $this->AttributeSets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attributeSet = $this->AttributeSets->patchEntity($attributeSet, $this->request->data);
            if ($this->AttributeSets->save($attributeSet)) {
                $this->Flash->success(__('The {0} has been saved.', __('attribute set')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attribute set')));
            }
        }
        $this->set(compact('attributeSet'));
        $this->set('_serialize', ['attributeSet']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Attribute Set id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attributeSet = $this->AttributeSets->get($id);
        if ($this->AttributeSets->delete($attributeSet)) {
            $this->Flash->success(__('The {0} has been deleted.', __('attribute set')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('attribute set')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
