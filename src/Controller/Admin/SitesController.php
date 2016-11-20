<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * Sites Controller
 *
 * @property \Banana\Model\Table\SitesTable $Sites
 */
class SitesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentSites']
        ];
        $this->set('sites', $this->paginate($this->Sites));
        $this->set('_serialize', ['sites']);
    }

    /**
     * View method
     *
     * @param string|null $id Site id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $site = $this->Sites->get($id, [
            'contain' => ['ParentSites', 'ChildSites']
        ]);
        $this->set('site', $site);
        $this->set('_serialize', ['site']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $site = $this->Sites->newEntity();
        if ($this->request->is('post')) {
            $site = $this->Sites->patchEntity($site, $this->request->data);
            if ($this->Sites->save($site)) {
                $this->Flash->success(__('The {0} has been saved.', __('site')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('site')));
            }
        }
        $parentSites = $this->Sites->ParentSites->find('list', ['limit' => 200]);
        $this->set(compact('site', 'parentSites'));
        $this->set('_serialize', ['site']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Site id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $site = $this->Sites->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $site = $this->Sites->patchEntity($site, $this->request->data);
            if ($this->Sites->save($site)) {
                $this->Flash->success(__('The {0} has been saved.', __('site')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('site')));
            }
        }
        $parentSites = $this->Sites->ParentSites->find('list', ['limit' => 200]);
        $this->set(compact('site', 'parentSites'));
        $this->set('_serialize', ['site']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Site id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $site = $this->Sites->get($id);
        if ($this->Sites->delete($site)) {
            $this->Flash->success(__('The {0} has been deleted.', __('site')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('site')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
