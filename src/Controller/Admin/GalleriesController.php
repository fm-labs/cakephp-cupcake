<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;

/**
 * Galleries Controller
 *
 * @property \Banana\Model\Table\GalleriesTable $Galleries
 */
class GalleriesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate['limit'] = 100;
        $this->paginate['order'] = ['Galleries.title' => 'ASC'];

        $this->set('galleries', $this->paginate($this->Galleries));
        $this->set('_serialize', ['galleries']);
    }

    /**
     * View method
     *
     * @param string|null $id Gallery id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gallery = $this->Galleries->get($id, [
            'contain' => ['Posts']
        ]);
        $this->set('gallery', $gallery);
        $this->set('_serialize', ['gallery']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $gallery = $this->Galleries->newEntity();
        if ($this->request->is('post')) {
            $gallery = $this->Galleries->patchEntity($gallery, $this->request->data);
            if ($this->Galleries->save($gallery)) {
                $this->Flash->success(__d('banana','The gallery has been saved.'));
                return $this->redirect(['action' => 'edit', $gallery->id]);
            } else {
                $this->Flash->error(__d('banana','The gallery could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('gallery'));
        $this->set('_serialize', ['gallery']);
    }

    public function addItem($id = null)
    {
        $gallery = $this->Galleries->get($id, [
            'contain' => []
        ]);

        $item = $this->Galleries->Posts->newEntity([
            'refscope' => 'Banana.Galleries',
            'refid' => $id
        ]);
        if ($this->request->is('post')) {
            $item = $this->Galleries->Posts->patchEntity($item, $this->request->data);
            if ($this->Galleries->Posts->save($item)) {
                $this->Flash->success(__d('banana','The gallery item has been saved.'));
                return $this->redirect(['action' => 'edit', $item->id]);
            } else {
                $this->Flash->error(__d('banana','The gallery could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('item'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Gallery id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $gallery = $this->Galleries->get($id, [
            'contain' => ['Posts']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gallery = $this->Galleries->patchEntity($gallery, $this->request->data);
            if ($this->Galleries->save($gallery)) {
                $this->Flash->success(__d('banana','The gallery has been saved.'));
                return $this->redirect(['action' => 'edit', $gallery->id]);
            } else {
                $this->Flash->error(__d('banana','The gallery could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('gallery'));
        $this->set('_serialize', ['gallery']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Gallery id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $gallery = $this->Galleries->get($id);
        if ($this->Galleries->delete($gallery)) {
            $this->Flash->success(__d('banana','The gallery has been deleted.'));
        } else {
            $this->Flash->error(__d('banana','The gallery could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
