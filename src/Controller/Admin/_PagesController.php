<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * Pages Controller
 *
 * @property \Banana\Model\Table\PagesTable $Pages
 */
class PagesController extends AppController
{

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
        $this->set('pages', $this->paginate($this->Pages));
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
            'contain' => ['ParentPages', 'ChildPages', 'PageModules']
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
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','page')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','page')));
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
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $page = $this->Pages->patchEntity($page, $this->request->data);
            if ($this->Pages->save($page)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','page')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','page')));
            }
        }
        $parentPages = $this->Pages->ParentPages->find('list', ['limit' => 200]);
        $this->set(compact('page', 'parentPages'));
        $this->set('_serialize', ['page']);
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
            $this->Flash->success(__d('banana','The {0} has been deleted.', __d('banana','page')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be deleted. Please, try again.', __d('banana','page')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
