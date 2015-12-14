<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * PageLayouts Controller
 *
 * @property \Banana\Model\Table\PageLayoutsTable $PageLayouts
 */
class PageLayoutsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('pageLayouts', $this->paginate($this->PageLayouts));
        $this->set('_serialize', ['pageLayouts']);
    }

    /**
     * View method
     *
     * @param string|null $id Page Layout id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pageLayout = $this->PageLayouts->get($id, [
            'contain' => []
        ]);
        $this->set('pageLayout', $pageLayout);
        $this->set('_serialize', ['pageLayout']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pageLayout = $this->PageLayouts->newEntity();
        if ($this->request->is('post')) {
            $pageLayout = $this->PageLayouts->patchEntity($pageLayout, $this->request->data);
            if ($this->PageLayouts->save($pageLayout)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','page layout')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','page layout')));
            }
        }
        $this->set(compact('pageLayout'));
        $this->set('_serialize', ['pageLayout']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Page Layout id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pageLayout = $this->PageLayouts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pageLayout = $this->PageLayouts->patchEntity($pageLayout, $this->request->data);
            if ($this->PageLayouts->save($pageLayout)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','page layout')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','page layout')));
            }
        }
        $this->set(compact('pageLayout'));
        $this->set('_serialize', ['pageLayout']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Page Layout id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pageLayout = $this->PageLayouts->get($id);
        if ($this->PageLayouts->delete($pageLayout)) {
            $this->Flash->success(__d('banana','The {0} has been deleted.', __d('banana','page layout')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be deleted. Please, try again.', __d('banana','page layout')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
