<?php
namespace Banana\Controller\Admin;

use Banana\Controller\Admin\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;

/**
 * Modules Controller
 *
 * @property \Banana\Model\Table\ModulesTable $Modules
 */
class ModulesController extends AppController
{

    public $modelClass = 'Banana.Modules';

    public function initialize()
    {
        parent::initialize();

        //$this->Auth->allow(['preview']);
    }

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

        //$module = $this->Modules->find()->where(['id' => $id])->all();
        //$module = $this->Modules->find('expanded')->where(['id' => $id])->first();

        $module = $this->Modules->modularize($module);

        $this->set('module', $module);
        $this->set('_serialize', ['module']);
    }

    public function previewModule($id = null)
    {
        $this->redirect(['prefix' => false, 'plugin' => 'Banana', 'controller' => 'Modules', 'action' => 'view', $id]);
    }

    public function preview()
    {
        $path = $this->request->query('path');
        $params = $this->request->query('params');
        if ($params) {
            $params = json_decode(base64_decode($params), true);
        }

        $this->set('modulePath', $path);
        $this->set('moduleParams', $params);

        $this->viewBuilder()
            ->layout('frontend')
            ->theme(Configure::read('Banana.Frontend.theme'))
            ->className('Banana.Frontend');
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
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','module')));
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
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','module')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','module')));
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
            $this->Flash->success(__d('banana','The {0} has been deleted.', __d('banana','module')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be deleted. Please, try again.', __d('banana','module')));
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
            $this->Flash->success(__d('banana','The {0} has been duplicated.', __d('banana','module')));
            return $this->redirect(['action' => 'edit', $duplicate->id]);
        } else {
            $this->Flash->error(__d('banana','The {0} could not be duplicated. Please, try again.', __d('banana','module')));
            return $this->redirect($this->referer(['action' => 'index']));
        }
    }
}
